<!-- Footer Khusus Tema Emerald -->
<footer class="bg-gradient-to-b from-emerald-950 via-slate-950 to-slate-950 text-emerald-100 pt-16 pb-12 mt-20 border-t border-emerald-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-emerald-900/60">
            <!-- Kolom 1: Profil & Identitas Desa Asri -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    @if($logo = village_logo())
                        <img src="{{ $logo }}" alt="Logo {{ \App\Models\Setting::get('village_name', 'Desa') }}" class="w-10 h-10 object-contain rounded-xl bg-white/10 p-1 border border-emerald-800 shadow-md">
                    @else
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center text-xl shadow-md shadow-emerald-500/20">
                            🌿
                        </div>
                    @endif
                    <div>
                        <h4 class="font-extrabold text-white text-base leading-tight">
                            {{ \App\Models\Setting::get('village_name', 'Desa Sukamaju') }}
                        </h4>
                        <span class="text-xs text-emerald-400 font-medium">Kawasan Asri & Digital</span>
                    </div>
                </div>
                <p class="text-xs text-emerald-200/80 leading-relaxed">
                    {{ \App\Models\Setting::get('app_tagline', 'Mewujudkan Desa Maju, Mandiri, dan Transparan Berbasis Digital') }}
                </p>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-900/60 border border-emerald-800 text-[11px] text-emerald-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    Portal Layanan Aktif 24 Jam
                </div>
            </div>

            <!-- Kolom 2: Layanan Mandiri & Persuratan -->
            <div class="space-y-3">
                <h5 class="text-xs font-bold text-white uppercase tracking-wider text-emerald-400">Layanan Warga</h5>
                <ul class="text-xs text-emerald-200/80 space-y-2">
                    <li><a href="{{ route('citizen.letters.create') }}" class="hover:text-emerald-300 transition flex items-center gap-1.5">&bull; Pengajuan Surat Keterangan Usaha (SKU)</a></li>
                    <li><a href="{{ route('citizen.letters.create') }}" class="hover:text-emerald-300 transition flex items-center gap-1.5">&bull; Surat Keterangan Domisili</a></li>
                    <li><a href="{{ route('citizen.letters.create') }}" class="hover:text-emerald-300 transition flex items-center gap-1.5">&bull; Surat Keterangan Tidak Mampu (SKTM)</a></li>
                    <li><a href="{{ route('citizen.letters.create') }}" class="hover:text-emerald-300 transition flex items-center gap-1.5">&bull; Pelayanan Kependudukan & Mutasi</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Keterbukaan Publik & Regulasi -->
            <div class="space-y-3">
                <h5 class="text-xs font-bold text-white uppercase tracking-wider text-emerald-400">PPID & Akuntabilitas</h5>
                <ul class="text-xs text-emerald-200/80 space-y-2">
                    <li><a href="{{ route('public.ppid.index') }}" class="hover:text-emerald-300 transition flex items-center gap-1.5">&bull; Profil & Maklumat Pelayanan PPID</a></li>
                    <li><a href="{{ route('public.ppid.documents') }}" class="hover:text-emerald-300 transition flex items-center gap-1.5">&bull; Repositori Dokumen Publik (DIP)</a></li>
                    <li><a href="{{ route('public.ppid.requests.create') }}" class="hover:text-emerald-300 transition flex items-center gap-1.5">&bull; Permohonan Informasi Daring</a></li>
                    <li><a href="{{ route('budgets.index') }}" class="hover:text-emerald-300 transition flex items-center gap-1.5">&bull; Laporan Realisasi APBDes Transparan</a></li>
                </ul>
            </div>

            <!-- Kolom 4: Kantor & Kontak Darurat -->
            <div class="space-y-3">
                <h5 class="text-xs font-bold text-white uppercase tracking-wider text-emerald-400">Kantor Pemerintah Desa</h5>
                <p class="text-xs text-emerald-200/80 leading-relaxed">
                    📍 {{ \App\Models\Setting::get('village_address', 'Jl. Raya Desa No. 01') }}
                </p>
                <div class="text-xs text-emerald-200/80 space-y-1">
                    <p>📞 Telp: {{ \App\Models\Setting::get('village_phone', '-') }}</p>
                    <p>✉️ Email: {{ \App\Models\Setting::get('village_email', 'kantor@desa.id') }}</p>
                </div>
                <div class="pt-2">
                    <span class="inline-block px-3 py-1 rounded-lg bg-emerald-900/80 text-[11px] font-semibold text-teal-300 border border-emerald-700/60">
                        Tema: Emerald Green Edition
                    </span>
                </div>
            </div>
        </div>

        <!-- Baris Bawah Hak Cipta -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-emerald-400/60 gap-4">
            <p>&copy; {{ date('Y') }} Pemerintah Desa {{ \App\Models\Setting::get('village_name', 'Sukamaju') }}. Sistem Informasi Desa (SiDesa).</p>
            <p class="flex flex-wrap items-center justify-center gap-x-2 gap-y-1">
                <span>Inovasi Tata Kelola Desa Mandiri & Terbuka</span>
                <span aria-hidden="true" class="text-emerald-700">•</span>
                <span>
                    Dikembangkan oleh
                    <a href="https://timacodecraft.com" target="_blank" rel="noopener" class="font-semibold text-emerald-300 hover:text-emerald-200 transition">TimaCodeCraft</a>
                </span>
            </p>
        </div>
    </div>
</footer>
