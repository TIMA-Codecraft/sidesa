@extends(theme_layout())

@section('title', '403 - Akses Dibatasi - ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju'))

@section('content')
<div class="py-20 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto text-center">
    <div class="w-24 h-24 mx-auto rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-6 shadow-inner">
        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
    </div>

    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 mb-4 tracking-wide uppercase">
        Galat 403 &bull; Akses Dibatasi
    </span>

    <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">
        Hak Akses Tidak Diizinkan
    </h1>

    <p class="text-slate-600 text-base sm:text-lg max-w-xl mx-auto leading-relaxed mb-8">
        {{ $exception?->getMessage() ?: 'Anda tidak memiliki wewenang untuk membuka tautan ini. Halaman ini memerlukan hak akses khusus atau dibatasi sesuai regulasi perlindungan data pribadi warga desa.' }}
    </p>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-sky-600 text-white font-semibold text-sm hover:bg-sky-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Kembali ke Beranda
        </a>
        @guest
        <a href="{{ route('admin.login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-sm hover:bg-slate-200 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            Masuk Sebagai Petugas
        </a>
        @endguest
    </div>
</div>
@endsection
