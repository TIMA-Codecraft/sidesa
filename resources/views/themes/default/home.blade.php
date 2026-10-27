@extends(theme_layout())

@section('title', \App\Models\Setting::get('app_title', 'SiDesa - Portal Resmi Desa'))

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-sky-900 via-sky-800 to-indigo-950 text-white py-24 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <div class="max-w-5xl mx-auto text-center relative z-10">
        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold bg-sky-500/20 text-sky-200 border border-sky-400/30 mb-6 backdrop-blur-md">
            ✨ Selamat Datang di Portal Resmi Desa
        </span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight">
            {{ \App\Models\Setting::get('village_name', 'Desa Sukamaju') }}
        </h1>
        <p class="mt-6 text-lg sm:text-xl text-sky-100 max-w-2xl mx-auto leading-relaxed">
            {{ \App\Models\Setting::get('app_tagline', 'Mewujudkan Desa Maju, Mandiri, dan Transparan Berbasis Digital') }}
        </p>

        <div class="mt-10 flex flex-wrap justify-center gap-4">
            <a href="{{ route('citizen.letters.create') }}" class="px-6 py-3.5 rounded-xl font-semibold bg-sky-500 hover:bg-sky-400 text-white transition shadow-lg shadow-sky-950/30 text-sm">
                ✉️ Ajukan Surat Online &rarr;
            </a>
            <a href="#profil" class="px-6 py-3.5 rounded-xl font-semibold bg-white/10 hover:bg-white/20 text-white transition backdrop-blur-md text-sm border border-white/20">
                📖 Profil & Transparansi
            </a>
        </div>
    </div>
</section>

<!-- Quick Highlights / Features -->
<section id="layanan" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto mb-14">
            @include('themes.default.partials.section-header', [
                'title' => 'Pelayanan Warga Mandiri',
                'description' => 'Kemudahan pengurusan dokumen administrasi dan persuratan desa tanpa harus antre lama di kantor desa.',
                'align' => 'center',
            ])
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition">
                <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-3xl mb-6">
                    📜
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Surat Keterangan Usaha (SKU)</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Pengajuan surat keterangan usaha untuk keperluan izin usaha, pengajuan perbankan, dan bantuan UMKM.
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl mb-6">
                    🏡
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Keterangan Domisili</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Pengesahan tempat tinggal warga maupun pendatang untuk berbagai kelengkapan administrasi instansi.
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl mb-6">
                    🤝
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Surat Pengantar SKCK</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Surat rekomendasi desa untuk permohonan Surat Keterangan Catatan Kepolisian di Polsek setempat.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Profil Desa Brief -->
<section id="profil" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-xs font-bold text-sky-600 uppercase tracking-wider block mb-2">Profil Singkat</span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-6">
                    Pemerintahan yang Responsif & Terbuka
                </h2>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">
                    {{ \App\Models\Setting::get('village_name', 'Desa Sukamaju') }} terus bertransformasi menuju tata kelola desa berbasis digital. Melalui sistem SiDesa, seluruh data kependudukan tercatat dengan rapi, pelayanan surat menyurat terdistribusi secara efisien, serta transparansi pengelolaan anggaran dapat diakses secara terbuka oleh masyarakat.
                </p>
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                    <div>
                        <span class="block text-2xl font-black text-slate-800">100%</span>
                        <span class="text-xs text-slate-500">Transparansi Anggaran</span>
                    </div>
                    <div>
                        <span class="block text-2xl font-black text-slate-800">24/7</span>
                        <span class="text-xs text-slate-500">Akses Portal Informasi</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-tr from-sky-100 to-indigo-50 p-8 rounded-2xl border border-sky-100 flex flex-col justify-center items-center text-center">
                @if($logo = village_logo())
                    <img src="{{ $logo }}" alt="Logo {{ \App\Models\Setting::get('village_name', 'Desa') }}" class="w-24 h-24 object-contain mb-4 drop-shadow-md">
                @else
                    <div class="text-8xl mb-4 select-none">🏛️</div>
                @endif
                <h3 class="font-bold text-xl text-slate-800">{{ \App\Models\Setting::get('village_name', 'Desa Sukamaju') }}</h3>
                <p class="text-xs text-slate-500 mt-1">Kode Wilayah: {{ \App\Models\Setting::get('village_code', '3201012001') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Kabar Desa Terkini -->
@if(isset($latestArticles) && $latestArticles->isNotEmpty())
<section id="informasi" class="py-20 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12">
            @include('themes.default.partials.section-header', [
                'eyebrow' => 'Warta & Dokumentasi',
                'title' => 'Kabar Desa Terkini',
                'description' => 'Ikuti berita terbaru, agenda kegiatan, dan kebijakan desa terkini.',
                'actionUrl' => route('articles.index'),
                'actionLabel' => 'Lihat Semua Kabar',
            ])
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($latestArticles as $article)
                @include('themes.default.partials.article-card', ['article' => $article])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
