@extends(theme_layout())

@section('title', '404 - Halaman Tidak Ditemukan - ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju'))

@section('content')
<div class="py-20 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto text-center">
    <div class="w-24 h-24 mx-auto rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-6 shadow-inner">
        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>

    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 mb-4 tracking-wide uppercase">
        Galat 404 &bull; Halaman Tidak Ditemukan
    </span>

    <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">
        Oops! Halaman Tidak Tersedia
    </h1>

    <p class="text-slate-600 text-base sm:text-lg max-w-xl mx-auto leading-relaxed mb-8">
        Mohon maaf, halaman yang Anda cari mungkin telah dipindahkan, diubah namanya, atau sedang tidak tersedia di portal resmi kami.
    </p>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-sky-600 text-white font-semibold text-sm hover:bg-sky-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Kembali ke Beranda
        </a>
        <a href="{{ route('public.ppid.documents') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-sm hover:bg-slate-200 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Dokumen Publik (DIP)
        </a>
        <a href="{{ route('citizen.letters.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-sm hover:bg-slate-200 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Layanan Surat Warga
        </a>
    </div>

    <!-- Tautan Menu Pintas -->
    <div class="mt-12 pt-8 border-t border-slate-200 grid grid-cols-1 sm:grid-cols-3 gap-4 text-left">
        <a href="{{ route('articles.index') }}" class="p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition border border-slate-200/60 block group">
            <span class="text-xs font-semibold text-sky-600 block uppercase tracking-wider mb-1">Berita Desa</span>
            <p class="text-sm font-bold text-slate-800 group-hover:text-sky-700">Baca Kabar & Artikel &rarr;</p>
        </a>
        <a href="{{ route('budgets.index') }}" class="p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition border border-slate-200/60 block group">
            <span class="text-xs font-semibold text-sky-600 block uppercase tracking-wider mb-1">Transparansi</span>
            <p class="text-sm font-bold text-slate-800 group-hover:text-sky-700">Realisasi APBDes &rarr;</p>
        </a>
        <a href="{{ route('map.index') }}" class="p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition border border-slate-200/60 block group">
            <span class="text-xs font-semibold text-sky-600 block uppercase tracking-wider mb-1">Peta Wilayah</span>
            <p class="text-sm font-bold text-slate-800 group-hover:text-sky-700">Geografis & Fasilitas &rarr;</p>
        </a>
    </div>
</div>
@endsection
