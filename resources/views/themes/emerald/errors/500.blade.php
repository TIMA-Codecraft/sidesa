@extends(theme_layout())

@section('title', '500 - Kesalahan Sistem - ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju'))

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full text-center bg-white/80 backdrop-blur-md p-8 sm:p-14 rounded-3xl border border-emerald-100 shadow-xl shadow-emerald-500/5">
        <div class="w-24 h-24 mx-auto rounded-3xl bg-gradient-to-tr from-rose-600 to-red-500 text-white flex items-center justify-center mb-6 shadow-lg shadow-rose-500/30">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 mb-4 tracking-wider uppercase">
            Galat 500 &bull; Gangguan Sistem
        </span>

        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-4">
            Terjadi Kesalahan Server
        </h1>

        <p class="text-slate-600 text-base max-w-lg mx-auto leading-relaxed mb-8">
            Sistem mengalami gangguan saat memproses halaman ini. Administrator dan tim teknis desa sedang memeriksa dan menangani permasalahan ini.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <button onclick="window.location.reload()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-sm hover:from-emerald-700 hover:to-teal-700 transition shadow-md shadow-emerald-600/20 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Muat Ulang Halaman
            </button>
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-slate-100 text-slate-700 font-bold text-sm hover:bg-slate-200 transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
