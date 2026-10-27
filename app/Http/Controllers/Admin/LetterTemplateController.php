<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterTemplate;
use App\Models\Resident;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LetterTemplateController extends Controller
{
    /**
     * Menampilkan daftar template surat.
     */
    public function index(): View
    {
        $templates = LetterTemplate::withCount('requests')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.letter_templates.index', compact('templates'));
    }

    /**
     * Form tambah template surat baru.
     */
    public function create(): View
    {
        return view('admin.letter_templates.create');
    }

    /**
     * Simpan template surat baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:30', 'unique:letter_templates,code'],
            'number_format' => ['nullable', 'string', 'max:120'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'content_template' => ['required', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['number_format'] = $request->filled('number_format') ? trim($request->input('number_format')) : null;

        LetterTemplate::create($validated);

        return redirect()->route('admin.letter-templates.index')
            ->with('success', 'Template surat berhasil ditambahkan.');
    }

    /**
     * Form edit template surat.
     */
    public function edit(LetterTemplate $letterTemplate): View
    {
        return view('admin.letter_templates.edit', compact('letterTemplate'));
    }

    /**
     * Update template surat.
     */
    public function update(Request $request, LetterTemplate $letterTemplate): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:30', 'unique:letter_templates,code,'.$letterTemplate->id],
            'number_format' => ['nullable', 'string', 'max:120'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'content_template' => ['required', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['number_format'] = $request->filled('number_format') ? trim($request->input('number_format')) : null;

        $letterTemplate->update($validated);

        return redirect()->route('admin.letter-templates.index')
            ->with('success', 'Template surat berhasil diperbarui.');
    }

    /**
     * Hapus template surat.
     */
    public function destroy(LetterTemplate $letterTemplate): RedirectResponse
    {
        if ($letterTemplate->requests()->exists()) {
            return redirect()->route('admin.letter-templates.index')
                ->with('error', 'Template tidak dapat dihapus karena sudah memiliki riwayat permohonan surat.');
        }

        $letterTemplate->delete();

        return redirect()->route('admin.letter-templates.index')
            ->with('success', 'Template surat berhasil dihapus.');
    }

    /**
     * Menampilkan pratinjau (preview) HTML template surat dengan contoh data warga.
     */
    public function preview(LetterTemplate $letterTemplate): View
    {
        $sampleResident = Resident::with('family')->first();

        $villageName = Setting::get('village_name', 'Sukamaju');
        $districtName = Setting::get('district_name', 'Cibinong');
        $regencyName = Setting::get('regency_name', 'Bogor');
        $villageAddress = Setting::get('village_address', 'Jl. Raya Desa No. 01');
        $postalCode = Setting::get('village_postal_code', '16911');
        $villagePhone = Setting::get('village_phone', '021-87654321');
        $villageEmail = Setting::get('village_email', 'desa.sukamaju@sidesa.id');

        $replacements = [
            '[NAMA_DESA]' => village_name(withPrefix: false),
            '[NAMA_KECAMATAN]' => (string) $districtName,
            '[NAMA_KABUPATEN]' => (string) $regencyName,
            '[NAMA]' => $sampleResident ? $sampleResident->name : 'Budi Santoso',
            '[NIK]' => $sampleResident ? $sampleResident->nik : '3201011508900001',
            '[NO_KK]' => $sampleResident?->family?->family_card_number ?? '3201011202150001',
            '[TEMPAT_TANGGAL_LAHIR]' => $sampleResident
                ? "{$sampleResident->birth_place}, ".($sampleResident->birth_date ? Carbon::parse($sampleResident->birth_date)->translatedFormat('d F Y') : '15 Agustus 1990')
                : 'Bogor, 15 Agustus 1990',
            '[JENIS_KELAMIN]' => ($sampleResident && $sampleResident->gender === 'P') ? 'Perempuan' : 'Laki-laki',
            '[AGAMA]' => $sampleResident->religion ?? 'Islam',
            '[STATUS_KAWIN]' => $sampleResident->marital_status ?? 'Kawin',
            '[PENDIDIKAN]' => $sampleResident->education_level ?? 'S1/D4',
            '[PEKERJAAN]' => $sampleResident->occupation ?? 'Wiraswasta',
            '[KEWARGANEGARAAN]' => $sampleResident->nationality ?? 'WNI',
            '[ALAMAT]' => $sampleResident?->family?->address ?? 'Jl. Merpati No. 12',
            '[RT]' => $sampleResident?->family?->rt ?? '001',
            '[RW]' => $sampleResident?->family?->rw ?? '002',
            '[DUSUN]' => $sampleResident?->family?->hamlet ?? 'Dusun Sukamaju',
            '[KEPERLUAN]' => 'Contoh keperluan pengajuan surat dinas / administrasi warga',
            '[NOMOR_SURAT]' => "470/001/{$letterTemplate->code}/Ds/".date('Y'),
            '[TANGGAL_SURAT]' => Carbon::now()->translatedFormat('d F Y'),
            '[NAMA_USAHA]' => 'Warung Kelontong Berkah Mandiri',
            '[BUSINESS_NAME]' => 'Warung Kelontong Berkah Mandiri',
            '[JENIS_USAHA]' => 'Warung Kelontong Berkah Mandiri',
            '[LOKASI_USAHA]' => 'RT 001 / RW 002 Dusun Sukamaju',
            '[BUSINESS_LOCATION]' => 'RT 001 / RW 002 Dusun Sukamaju',
            '[ALAMAT_USAHA]' => 'RT 001 / RW 002 Dusun Sukamaju',
            '[LAMA_USAHA]' => '3 (Tiga) Tahun',
            '[BUSINESS_SINCE]' => '3 (Tiga) Tahun',
            '[LAMA_BERDIRI]' => '3 (Tiga) Tahun',
            '[TAHUN_BERDIRI]' => '2021',
            '[NAMA_INSTANSI]' => 'Universitas Indonesia',
            '[NAMA_SEKOLAH]' => 'SMA Negeri 1 Sukamaju',
            '[SCHOOL_OR_INSTITUTION]' => 'Universitas Indonesia',
        ];

        $renderedContent = str_replace(array_keys($replacements), array_values($replacements), $letterTemplate->content_template);

        return view('admin.letter_templates.preview', compact(
            'letterTemplate',
            'renderedContent',
            'villageName',
            'districtName',
            'regencyName',
            'villageAddress',
            'postalCode',
            'villagePhone',
            'villageEmail'
        ));
    }
}
