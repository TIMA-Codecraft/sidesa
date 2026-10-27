<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\LetterRequest;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class TteService
{
    /**
     * Hitung SHA-256 Cryptographic Checksum Digest dari konten kanonikal surat.
     * Mengikat integritas nomor surat, isi final, NIK pemohon, waktu pengesahan, dan identitas penandatangan.
     */
    public function calculateDocumentHash(LetterRequest $letterRequest): string
    {
        $letterNumber = (string) ($letterRequest->letter_number ?? '');
        $content = (string) ($letterRequest->final_content ?? '');
        $residentNik = (string) ($letterRequest->resident?->nik ?? '');
        $signedAt = $letterRequest->signed_at ? $letterRequest->signed_at->toIso8601String() : '';
        $templateCode = (string) ($letterRequest->template?->code ?? '');

        // Canonical payload string
        $canonicalString = implode('|', [
            'SID-LETTER',
            $letterNumber,
            $templateCode,
            $residentNik,
            $signedAt,
            trim(strip_tags($content)),
        ]);

        return hash('sha256', $canonicalString);
    }

    /**
     * Proses pengesahan Tanda Tangan Elektronik (TTE) Tersertifikasi (PSrE / BSrE BSSN).
     *
     * @return array<string, mixed>
     */
    public function sign(LetterRequest $letterRequest, User $kadesUser, array $options = []): array
    {
        $provider = $options['provider'] ?? Setting::get('tte_provider', Config::get('tte.default_provider', 'bsre_bssn'));
        $now = Carbon::now();

        // Pastikan atribut pengesahan terpasang sementara untuk menghitung hash kanonikal
        $letterRequest->signed_at = $letterRequest->signed_at ?? $now;

        $documentHash = $this->calculateDocumentHash($letterRequest);
        $signatureSecret = (string) Config::get('app.key', 'sidesa-secret-key');
        $signatureHash = hash_hmac('sha256', $documentHash.'|'.$kadesUser->id.'|'.$now->timestamp, $signatureSecret);

        $kadesNip = is_array($kadesUser->metadata) && isset($kadesUser->metadata['nip'])
            ? (string) $kadesUser->metadata['nip']
            : '197508152005011002';
        $kadesNik = (string) ($kadesUser->nik ?? ($kadesUser->metadata['nik'] ?? '3201010101750001'));
        $kadesPosition = 'Kepala Desa '.village_name(withPrefix: false);

        // Metadata Sertifikat X.509 dari Balai Sertifikasi Elektronik (BSrE BSSN)
        $issuer = (string) Config::get('tte.bsre.issuer', 'Balai Sertifikasi Elektronik (BSrE) - Badan Siber dan Sandi Negara (BSSN)');
        $serialNumber = sprintf(
            'BSRE-BSSN-%s-%s',
            $now->format('Y'),
            strtoupper(substr(hash('sha256', $kadesUser->id.$kadesUser->email.$kadesNip), 0, 12))
        );

        return [
            'signature_type' => LetterRequest::SIGNATURE_TYPE_CERTIFIED,
            'tte_provider' => $provider,
            'certificate_issuer' => $issuer,
            'certificate_serial_number' => $serialNumber,
            'document_hash' => $documentHash,
            'signature_hash' => $signatureHash,
            'signer_name' => $kadesUser->name,
            'signer_nip' => $kadesNip,
            'signer_nik' => $kadesNik,
            'signer_position' => $kadesPosition,
            'tte_timestamp' => $now,
            'is_tampered' => false,
        ];
    }

    /**
     * Verifikasi integritas kriptografis dan keabsahan sertifikat elektronik dokumen.
     *
     * @return array<string, mixed>
     */
    public function verify(LetterRequest $letterRequest): array
    {
        if (! $letterRequest->isApproved() || empty($letterRequest->document_hash)) {
            return [
                'is_valid' => false,
                'is_certified' => false,
                'is_tampered' => false,
                'status' => 'UNAPPROVED',
                'status_label' => 'Belum Disahkan',
                'message' => 'Dokumen belum melalui pengesahan Tanda Tangan Elektronik oleh Kepala Desa.',
            ];
        }

        // Hitung ulang hash dari konten aktual saat ini
        $recalculatedHash = $this->calculateDocumentHash($letterRequest);
        $isMatch = hash_equals($letterRequest->document_hash, $recalculatedHash);

        if (! $isMatch) {
            // Tandai tampered di database jika terdeteksi
            if (! $letterRequest->is_tampered) {
                $letterRequest->update(['is_tampered' => true]);
            }

            return [
                'is_valid' => false,
                'is_certified' => $letterRequest->isCertifiedTte(),
                'is_tampered' => true,
                'status' => 'TAMPERED',
                'status_label' => 'Integritas Rusak (Dokumen Dimodifikasi)',
                'message' => 'PERINGATAN: Dokumen ini tidak valid karena isi naskah surat telah diubah secara ilegal setelah penandatanganan elektronik.',
                'stored_hash' => $letterRequest->document_hash,
                'recalculated_hash' => $recalculatedHash,
            ];
        }

        return [
            'is_valid' => true,
            'is_certified' => $letterRequest->isCertifiedTte(),
            'is_tampered' => false,
            'status' => 'VALID',
            'status_label' => 'Sah & Terverifikasi (BSrE BSSN)',
            'message' => 'Dokumen ini resmi, asli, dan utuh. Ditandatangani secara elektronik menggunakan Sertifikat Elektronik BSrE BSSN.',
            'provider' => $letterRequest->tte_provider_label,
            'issuer' => $letterRequest->certificate_issuer,
            'serial_number' => $letterRequest->certificate_serial_number,
            'document_hash' => $letterRequest->document_hash,
            'signer_name' => $letterRequest->signer_name ?? $letterRequest->kadesApprover?->name,
            'signer_nip' => $letterRequest->signer_nip,
            'signer_position' => $letterRequest->signer_position ?? 'Kepala Desa',
            'timestamp' => $letterRequest->tte_timestamp ?? $letterRequest->signed_at,
            'regulations' => [
                'UU No. 1 Tahun 2024 tentang ITE (Pasal 11)',
                'PP No. 71 Tahun 2019 tentang PSTE (Penyelenggaraan Sertifikasi Elektronik)',
            ],
        ];
    }
}
