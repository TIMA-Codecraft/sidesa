<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keaslian Dokumen & TTE - SiDesa {{ $villageName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-100 min-h-screen py-10 px-4 flex items-center justify-center">
    <div class="max-w-xl w-full bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 text-white text-center">
            <div class="inline-flex p-3 rounded-2xl bg-white/10 mb-3 backdrop-blur shadow-inner">
                🏛️
            </div>
            <h1 class="text-lg font-bold">Pemerintah Desa {{ village_name(withPrefix: false) }}</h1>
            <p class="text-xs text-slate-300">Kec. {{ $districtName }}, Kab. {{ $regencyName }}</p>
            <div class="mt-2 text-[11px] font-mono tracking-widest text-emerald-300 uppercase">
                Sistem Verifikasi Dokumen &amp; TTE Tersertifikasi (UU ITE No. 1/2024)
            </div>
        </div>

        <div class="p-6 space-y-6">
            @if($letter && $letter->isApproved())
                @if(isset($tteVerification) && $tteVerification['is_tampered'])
                    <!-- Peringatan Integritas Rusak / Tampered -->
                    <div class="bg-rose-50 border-2 border-rose-400 rounded-2xl p-5 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-rose-100 text-rose-600 mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h2 class="text-base font-black text-rose-900 uppercase">DOKUMEN TIDAK VALID / INTEGRITAS RUSAK</h2>
                        <p class="text-xs text-rose-700 mt-1 font-semibold leading-relaxed">
                            {{ $tteVerification['message'] }}
                        </p>
                        <div class="mt-3 p-2 rounded-xl bg-white/80 border border-rose-200 text-[10px] font-mono text-rose-800 text-left overflow-x-auto">
                            <div>Hash Asli : {{ $tteVerification['stored_hash'] ?? '-' }}</div>
                            <div>Hash Baru : {{ $tteVerification['recalculated_hash'] ?? '-' }}</div>
                        </div>
                    </div>
                @else
                    <!-- Status Valid & Sah -->
                    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h2 class="text-base font-black text-emerald-900 uppercase">DOKUMEN SAH &amp; TERVERIFIKASI</h2>
                        @if($letter->isCertifiedTte())
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-700 text-white shadow-2xs">
                                🛡️ TTE TERSERTIFIKASI (BSrE BSSN)
                            </span>
                        @endif
                        <p class="text-xs text-emerald-700 mt-2">
                            Dokumen ini resmi diterbitkan dan ditandatangani secara elektronik (TTE) menggunakan sertifikat elektronik yang diakui secara hukum.
                        </p>
                    </div>
                @endif

                <!-- Detail Dokumen Surat -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2.5 text-xs">
                    <div class="font-bold text-slate-700 text-[11px] uppercase tracking-wider pb-1.5 border-b border-slate-200">
                        📄 Informasi Dokumen Pelayanan
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Nomor Surat Resmi</span>
                        <span class="font-bold text-slate-800 font-mono">{{ $letter->letter_number }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Jenis Dokumen</span>
                        <span class="font-semibold text-slate-800 text-right">{{ $letter->template->name }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Nama Pemohon / Warga</span>
                        <span class="font-bold text-slate-800">{{ $letter->resident->name }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">NIK (Dilindungi UU PDP)</span>
                        <span class="font-mono text-slate-800">
                            {{ substr($letter->resident->nik, 0, 6) . '******' . substr($letter->resident->nik, -4) }}
                        </span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-slate-500">Keperluan</span>
                        <span class="text-slate-800 text-right italic font-medium max-w-[240px] truncate">{{ $letter->purpose }}</span>
                    </div>
                </div>

                <!-- Detail Yuridis Sertifikasi Elektronik (BSrE BSSN) -->
                @if($letter->isCertifiedTte() || $letter->certificate_issuer)
                    <div class="bg-indigo-50/60 p-4 rounded-2xl border border-indigo-200 space-y-2.5 text-xs">
                        <div class="flex items-center justify-between pb-1.5 border-b border-indigo-200">
                            <span class="font-bold text-indigo-950 text-[11px] uppercase tracking-wider">
                                🔐 Keabsahan Yuridis TTE (UU No. 1/2024)
                            </span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-indigo-100 text-indigo-800">
                                PP 71/2019
                            </span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-indigo-100">
                            <span class="text-slate-500">Penerbit Sertifikat (PSrE)</span>
                            <span class="font-bold text-indigo-900 text-right">{{ $letter->certificate_issuer ?? 'Balai Sertifikasi Elektronik (BSrE) - BSSN' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-indigo-100">
                            <span class="text-slate-500">Nomor Seri Sertifikat X.509</span>
                            <span class="font-mono text-indigo-900 font-semibold">{{ $letter->certificate_serial_number ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-indigo-100">
                            <span class="text-slate-500">Penandatangan Resmi</span>
                            <div class="text-right">
                                <span class="font-bold text-slate-800 block">{{ $letter->signer_name ?? $letter->kadesApprover->name ?? 'Kepala Desa' }}</span>
                                <span class="text-[10px] text-slate-500 block">{{ $letter->signer_position ?? 'Kepala Desa' }} (NIP. {{ $letter->signer_nip ?? '-' }})</span>
                            </div>
                        </div>
                        <div class="flex justify-between py-1 border-b border-indigo-100">
                            <span class="text-slate-500">Stempel Waktu Digital (TSA)</span>
                            <span class="font-medium text-slate-800">
                                {{ ($letter->tte_timestamp ?? $letter->signed_at)?->translatedFormat('d F Y - H:i:s') }} {{ timezone_label() }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[10px]">SHA-256 Checksum (Integritas Dokumen):</span>
                            <span class="font-mono text-[10px] text-indigo-700 bg-white px-2 py-1 rounded border border-indigo-200 block break-all mt-0.5 select-all">
                                {{ $letter->document_hash ?? '-' }}
                            </span>
                        </div>
                    </div>
                @endif

                <div class="text-center pt-2">
                    <p class="text-[10px] text-slate-400">
                        Keabsahan data diverifikasi langsung dari basis data server SiDesa (Sistem Informasi Desa) dan dijamin integritasnya oleh Balai Sertifikasi Elektronik (BSrE).
                    </p>
                </div>
            @else
                <!-- Status Tidak Ditemukan / Tidak Sah -->
                <div class="bg-rose-50 border border-rose-200 rounded-2xl p-6 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-rose-100 text-rose-600 mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <h2 class="text-base font-bold text-rose-900">DOKUMEN TIDAK TERDAFTAR</h2>
                    <p class="text-xs text-rose-700 mt-2">
                        QR Code atau token dokumen tidak cocok dengan catatan resmi server atau belum selesai disahkan oleh Kepala Desa.
                    </p>
                </div>
            @endif

            <div class="pt-2 text-center border-t border-slate-100">
                <a href="{{ route('home') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold inline-flex items-center space-x-1">
                    <span>&larr;</span>
                    <span>Kembali ke Beranda SiDesa</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
