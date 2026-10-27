@extends(theme_layout())

@section('title', \App\Models\Setting::get('app_title', 'SiDesa - Portal Resmi Desa Sukamaju'))

@section('content')
<!-- Hero Section Emerald (Modern Split 2-Column with Interactive Card) -->
<section class="relative pt-8 pb-16 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-950 rounded-3xl p-8 sm:p-12 lg:p-16 text-white shadow-2xl shadow-emerald-950/20 overflow-hidden">
            <!-- Ambient Pattern Background -->
            <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -top-20 w-80 h-80 bg-teal-400/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center relative z-10">
                <!-- Kolom Kiri: Branding & Pencarian Cepat (7 Kolom) -->
                <div class="lg:col-span-7 space-y-6 text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 text-xs font-semibold backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        🌿 Portal Resmi Desa Wisata & Kawasan Asri
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight">
                        {{ \App\Models\Setting::get('village_name', 'Desa Sukamaju') }}
                    </h1>

                    <p class="text-emerald-100/90 text-sm sm:text-base lg:text-lg leading-relaxed max-w-xl">
                        {{ \App\Models\Setting::get('app_tagline', 'Mewujudkan Desa Maju, Mandiri, dan Transparan Berbasis Digital') }}
                    </p>

                    <!-- Pencarian Berita & Layanan Cepat -->
                    <form action="{{ route('articles.index') }}" method="GET" class="max-w-lg bg-white/10 backdrop-blur-md border border-white/20 p-1.5 rounded-2xl flex items-center gap-2 shadow-inner">
                        <div class="pl-3 text-emerald-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" placeholder="Cari informasi berita, pengumuman, atau surat..." class="bg-transparent border-0 text-white placeholder-emerald-200/60 text-xs sm:text-sm focus:outline-none focus:ring-0 flex-1">
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs transition shadow-md">
                            Cari
                        </button>
                    </form>

                    <!-- Tombol Aksi Cepat -->
                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="{{ route('citizen.letters.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 text-slate-950 font-bold text-xs hover:opacity-90 transition shadow-lg shadow-emerald-950/40">
                            <span>✉️ Ajukan Surat Online</span>
                            <span class="text-base">&rarr;</span>
                        </a>
                        <a href="{{ route('public.ppid.documents') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/20 backdrop-blur-md transition">
                            <span>📑 Dokumen Publik (DIP)</span>
                        </a>
                    </div>
                </div>

                <!-- Kolom Kanan: Card Status & Indikator Desa (5 Kolom) -->
                <div class="lg:col-span-5">
                    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 sm:p-8 rounded-3xl shadow-xl text-white space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-white/10">
                            <div>
                                <span class="text-[11px] uppercase tracking-wider text-emerald-300 font-semibold block">Aparatur Pemerintahan</span>
                                <h3 class="text-base font-bold text-white">{{ \App\Models\Setting::get('kades_name', 'Kepala Desa Sukamaju') }}</h3>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-400/20 text-emerald-300 text-[11px] font-semibold border border-emerald-400/30">
                                🟢 Kantor Siap Melayani
                            </span>
                        </div>

                        <!-- Mini Stats Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                                <span class="text-[11px] text-emerald-200 block">Total Penduduk</span>
                                <span class="text-2xl font-black text-white">
                                    {{ number_format(\App\Models\Resident::count()) }}
                                </span>
                                <span class="text-[10px] text-emerald-300/80 block mt-0.5">Jiwa Terdata</span>
                            </div>
                            <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                                <span class="text-[11px] text-emerald-200 block">Kepala Keluarga</span>
                                <span class="text-2xl font-black text-white">
                                    {{ number_format(\App\Models\Family::count()) }}
                                </span>
                                <span class="text-[10px] text-emerald-300/80 block mt-0.5">Kartu Keluarga (KK)</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-gradient-to-r from-emerald-600/30 to-teal-600/30 border border-emerald-400/30 flex items-center justify-between">
                            <div>
                                <span class="text-[11px] text-emerald-200 block">Transparansi Anggaran</span>
                                <span class="text-xs font-bold text-white">APBDes Tahun Anggaran {{ date('Y') }}</span>
                            </div>
                            <a href="{{ route('budgets.index') }}" class="text-xs font-bold text-emerald-300 hover:text-white transition flex items-center gap-1">
                                Rincian &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Action Interactive Ribbon (Pill Dock) -->
<section class="px-4 sm:px-6 lg:px-8 -mt-6 relative z-20">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl shadow-emerald-950/5 border border-emerald-100 p-4 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <a href="{{ route('citizen.letters.create') }}" class="p-3 rounded-xl hover:bg-emerald-50 transition text-center group block">
                <span class="text-2xl block mb-1 group-hover:scale-110 transition-transform">✉️</span>
                <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 block">Layanan Surat</span>
                <span class="text-[10px] text-slate-500">Ajukan Mandiri</span>
            </a>
            <a href="{{ route('public.ppid.documents') }}" class="p-3 rounded-xl hover:bg-emerald-50 transition text-center group block">
                <span class="text-2xl block mb-1 group-hover:scale-110 transition-transform">📑</span>
                <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 block">Dokumen DIP</span>
                <span class="text-[10px] text-slate-500">Katalog PPID</span>
            </a>
            <a href="{{ route('budgets.index') }}" class="p-3 rounded-xl hover:bg-emerald-50 transition text-center group block">
                <span class="text-2xl block mb-1 group-hover:scale-110 transition-transform">📊</span>
                <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 block">APBDes</span>
                <span class="text-[10px] text-slate-500">Transparansi</span>
            </a>
            <a href="{{ route('map.index') }}" class="p-3 rounded-xl hover:bg-emerald-50 transition text-center group block">
                <span class="text-2xl block mb-1 group-hover:scale-110 transition-transform">🗺️</span>
                <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 block">Peta Desa</span>
                <span class="text-[10px] text-slate-500">Peta GIS & Zonasi</span>
            </a>
            <a href="{{ route('galleries.index') }}" class="p-3 rounded-xl hover:bg-emerald-50 transition text-center group block">
                <span class="text-2xl block mb-1 group-hover:scale-110 transition-transform">📸</span>
                <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 block">Potret Desa</span>
                <span class="text-[10px] text-slate-500">Galeri Kegiatan</span>
            </a>
            <a href="{{ route('public.ppid.tracking') }}" class="p-3 rounded-xl hover:bg-emerald-50 transition text-center group block">
                <span class="text-2xl block mb-1 group-hover:scale-110 transition-transform">🔍</span>
                <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 block">Lacak Tiket</span>
                <span class="text-[10px] text-slate-500">Cek Status PPID</span>
            </a>
        </div>
    </div>
</section>

<!-- 2-Column Asymmetrical Layout: Feed (Left 8 Cols) + Sticky Sidebar Hub (Right 4 Cols) -->
<section class="py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Kolom Kiri: Warta Berita & Informasi Utama (8 Kolom) -->
        <div class="lg:col-span-8 space-y-12">
            <!-- Header Section Berita -->
            <div class="border-b border-emerald-100 pb-4">
                @include('themes.emerald.partials.section-header', [
                    'eyebrow' => 'Publikasi Resmi',
                    'title' => 'Kabar & Warta Desa',
                    'actionUrl' => route('articles.index'),
                    'actionLabel' => 'Lihat Semua',
                ])
            </div>

            <!-- Articles Feed Grid -->
            @if(isset($latestArticles) && $latestArticles->isNotEmpty())
                @php
                    $leadArticle = $latestArticles->first();
                    $secondaryArticles = $latestArticles->slice(1);
                @endphp

                <!-- Lead Featured Story -->
                <article class="bg-white rounded-3xl overflow-hidden border border-emerald-100 shadow-sm hover:shadow-md transition group">
                    <div class="relative h-64 sm:h-80 bg-slate-100 overflow-hidden">
                        <img src="{{ $leadArticle->cover_image ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $leadArticle->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6 text-white">
                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-500 text-slate-950 uppercase tracking-wider mb-2">
                                Warta Utama
                            </span>
                            <h3 class="text-xl sm:text-2xl font-black leading-snug hover:text-emerald-300 transition">
                                <a href="{{ route('articles.show', $leadArticle->slug) }}">
                                    {{ $leadArticle->title }}
                                </a>
                            </h3>
                            <div class="mt-2 flex items-center gap-4 text-xs text-white/80">
                                <span>📅 {{ optional($leadArticle->published_at)->translatedFormat('d M Y') }}</span>
                                <span>&bull;</span>
                                <span>👤 {{ $leadArticle->author?->name ?? 'Admin Desa' }}</span>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Sub-grid of secondary news -->
                @if($secondaryArticles->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @foreach($secondaryArticles as $art)
                            @include('themes.emerald.partials.article-card', [
                                'article' => $art,
                                'compact' => true,
                                'showSummary' => false,
                                'showReadingTime' => false,
                                'showAuthor' => false,
                                'readLabel' => 'Baca Lengkap',
                            ])
                        @endforeach
                    </div>
                @endif
            @else
                @include('themes.emerald.partials.empty-state', [
                    'title' => 'Belum ada artikel warta yang dipublikasikan.',
                    'compact' => true,
                ])
            @endif

            <!-- Banner Transparansi PPID & Informasi Publik -->
            <div class="bg-gradient-to-r from-emerald-800 to-teal-900 rounded-3xl p-8 text-white flex flex-col sm:flex-row items-center justify-between gap-6 shadow-lg shadow-emerald-950/10">
                <div class="space-y-2 text-left">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-white/20 text-white uppercase tracking-wider">
                        Hak Warga Atas Informasi
                    </span>
                    <h3 class="text-xl font-extrabold tracking-tight">Keterbukaan Informasi Publik Desa (PPID)</h3>
                    <p class="text-xs text-emerald-100 max-w-lg leading-relaxed">
                        Sesuai amanat UU KIP No. 14/2008 & Perki No. 1/2018, warga berhak mengetahui perencanaan anggaran, laporan kinerja, dan peraturan desa secara akuntabel.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <a href="{{ route('public.ppid.index') }}" class="px-5 py-3 rounded-xl bg-white text-slate-900 hover:bg-emerald-50 font-bold text-xs transition text-center shadow-md">
                        Kunjungi PPID &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Sidebar Hub Interaktif (4 Kolom) -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Widget 1: Layanan Surat Cepat Warga -->
            <div class="bg-white rounded-3xl border border-emerald-100 p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                        ✉️
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Ajukan Surat Mandiri</h3>
                </div>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    Ajukan surat pengantar langsung secara daring dan pantau proses verifikasi dari Ketua RT hingga Kepala Desa.
                </p>
                <div class="space-y-2">
                    <a href="{{ route('citizen.letters.create') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 transition text-xs font-semibold text-slate-700 border border-slate-100">
                        <span>Surat Keterangan Usaha (SKU)</span>
                        <span class="text-slate-400">&rarr;</span>
                    </a>
                    <a href="{{ route('citizen.letters.create') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 transition text-xs font-semibold text-slate-700 border border-slate-100">
                        <span>Surat Keterangan Domisili</span>
                        <span class="text-slate-400">&rarr;</span>
                    </a>
                    <a href="{{ route('citizen.letters.create') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 transition text-xs font-semibold text-slate-700 border border-slate-100">
                        <span>Surat Keterangan Tidak Mampu</span>
                        <span class="text-slate-400">&rarr;</span>
                    </a>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <a href="{{ route('citizen.letters.create') }}" class="block w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs text-center transition shadow-sm">
                        Pilih Surat Lainnya &rarr;
                    </a>
                </div>
            </div>

            <!-- Widget 2: Nomor Darurat & Tanggap Siaga -->
            <div class="bg-gradient-to-br from-slate-900 to-emerald-950 rounded-3xl p-6 text-white shadow-sm space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-white/10">
                    <span class="text-lg">🚨</span>
                    <h3 class="font-bold text-sm text-white">Kontak Siaga & Darurat Desa</h3>
                </div>
                <ul class="text-xs space-y-3 text-slate-300">
                    <li class="flex items-center justify-between">
                        <span>Ambulans Siaga Desa:</span>
                        <strong class="text-emerald-400 font-mono">0811-9988-7766</strong>
                    </li>
                    <li class="flex items-center justify-between">
                        <span>Bhabinkamtibmas:</span>
                        <strong class="text-emerald-400 font-mono">0812-3456-7891</strong>
                    </li>
                    <li class="flex items-center justify-between">
                        <span>Babinsa:</span>
                        <strong class="text-emerald-400 font-mono">0813-9876-5432</strong>
                    </li>
                    <li class="flex items-center justify-between">
                        <span>Posko Tanggap Bencana:</span>
                        <strong class="text-emerald-400 font-mono">0815-5544-3322</strong>
                    </li>
                </ul>
            </div>

            <!-- Widget 3: Halaman Profil & Visi Misi -->
            @if(isset($featuredPages) && $featuredPages->isNotEmpty())
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4">
                    <h3 class="font-extrabold text-slate-900 text-sm pb-2 border-b border-slate-100">
                        Halaman Informasi Profil
                    </h3>
                    <div class="space-y-2">
                        @foreach($featuredPages as $page)
                            <a href="{{ route('pages.show', $page->slug) }}" class="block p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 transition text-xs font-semibold text-slate-700">
                                📖 {{ $page->title }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
