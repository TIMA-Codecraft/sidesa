@extends(theme_layout())

@section('title', '404 - Halaman Tidak Ditemukan - ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju'))

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full text-center bg-white/80 backdrop-blur-md p-8 sm:p-14 rounded-3xl border border-emerald-100 shadow-xl shadow-emerald-500/5">
        <div class="w-24 h-24 mx-auto rounded-3xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center mb-6 shadow-lg shadow-emerald-500/30">
            <span class="text-3xl font-black">404</span>
        </div>

        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 mb-4 tracking-wider uppercase">
            Tema Emerald &bull; Tidak Ditemukan
        </span>

        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-4">
            Alamat Halaman Tidak Ada
        </h1>

        <p class="text-slate-600 text-base max-w-lg mx-auto leading-relaxed mb-8">
            Halaman atau konten yang Anda cari tidak dapat ditemukan pada server portal Desa Sukamaju. Silakan periksa kembali URL atau gunakan navigasi berikut.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-sm hover:from-emerald-700 hover:to-teal-700 transition shadow-md shadow-emerald-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Kembali ke Beranda
            </a>
            <a href="{{ route('public.ppid.documents') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-slate-100 text-slate-700 font-bold text-sm hover:bg-slate-200 transition">
                Daftar Dokumen Publik
            </a>
        </div>
    </div>
</div>
@endsection
