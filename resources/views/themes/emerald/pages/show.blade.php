@extends(theme_layout())

@section('title', $page->title . ' - ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju'))

@section('content')
@component('themes.emerald.partials.page-hero', [
    'breadcrumbs' => [
        ['label' => 'Beranda', 'url' => route('home'), 'icon' => '🏡'],
        ['label' => 'Informasi & Profil', 'muted' => true],
        ['label' => $page->title, 'truncate' => true],
    ],
    'eyebrow' => 'Dokumentasi Resmi Desa ' . \App\Models\Setting::get('village_name', 'Sukamaju'),
    'eyebrowIcon' => '🏛️',
    'title' => $page->title,
])
    @if($page->summary)
        <div class="border-l-4 border-emerald-400/80 pl-4 py-1">
            <p class="text-sm sm:text-base lg:text-lg text-emerald-100/90 leading-relaxed font-normal max-w-3xl">
                {{ $page->summary }}
            </p>
        </div>
    @endif

    <div class="pt-4 flex flex-wrap items-center justify-between gap-4 text-xs text-emerald-200/80 border-t border-white/10">
        <div class="flex flex-wrap items-center gap-4">
            <span class="flex items-center gap-1.5 font-medium text-emerald-200">
                <span>📅</span>
                <span>Diperbarui: {{ $page->updated_at->translatedFormat('d F Y') }}</span>
            </span>
            <span class="text-emerald-500">&bull;</span>
            <span class="flex items-center gap-1.5 text-emerald-200">
                <span>👁️</span>
                <span>{{ number_format($page->view_count) }} kali dibaca</span>
            </span>
            <span class="text-emerald-500">&bull;</span>
            <span class="flex items-center gap-1.5 text-emerald-200">
                <span>✍️</span>
                <span>{{ $page->author?->name ?? 'Admin Desa' }}</span>
            </span>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-md border border-white/20 transition shadow-sm cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Dokumen</span>
            </button>
        </div>
    </div>
@endcomponent

<section class="py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10">
        <main class="lg:col-span-8">
            <article class="bg-white p-6 sm:p-10 rounded-3xl border border-emerald-100/80 shadow-sm space-y-8">
                @if($page->cover_image)
                    <div class="rounded-2xl overflow-hidden shadow-sm border border-emerald-100">
                        <img src="{{ $page->cover_image }}" alt="{{ $page->title }}" class="w-full max-h-[450px] object-cover">
                    </div>
                @endif

                <div class="prose prose-slate prose-emerald max-w-none text-slate-700 leading-relaxed text-sm sm:text-base space-y-4">
                    {!! $page->rendered_body !!}
                </div>

                <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                    <span class="font-medium text-emerald-800">
                        Pemerintah Desa {{ \App\Models\Setting::get('village_name', 'Sukamaju') }}
                    </span>
                    <a href="#top" class="text-emerald-700 hover:text-emerald-800 font-bold inline-flex items-center gap-1">
                        Kembali ke Atas &uarr;
                    </a>
                </div>
            </article>
        </main>

        <aside class="lg:col-span-4 space-y-8">
            <div class="bg-white p-6 rounded-3xl border border-emerald-100 shadow-sm space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <span class="text-lg">📑</span>
                    <h3 class="font-extrabold text-slate-900 text-sm">Halaman Profil Terkait</h3>
                </div>

                <nav class="space-y-1.5">
                    @foreach($otherPages as $other)
                        <a href="{{ route('pages.show', $other->slug) }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ $other->id === $page->id ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-700 hover:bg-emerald-50 hover:text-emerald-700' }}">
                            <span>{{ $other->title }}</span>
                            <span class="text-[11px] opacity-70">&rarr;</span>
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="bg-gradient-to-br from-emerald-900 to-teal-950 p-6 rounded-3xl text-white shadow-md shadow-emerald-950/10 space-y-4">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-xl">
                    ✉️
                </div>
                <div>
                    <h4 class="font-bold text-sm text-white">Butuh Surat Pengantar?</h4>
                    <p class="text-xs text-emerald-200/80 mt-1 leading-relaxed">
                        Ajukan permohonan surat keterangan warga secara daring tanpa perlu antre di kantor desa.
                    </p>
                </div>
                <a href="{{ route('citizen.letters.create') }}" class="block w-full py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs text-center transition shadow-md">
                    Ajukan Surat Mandiri &rarr;
                </a>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-emerald-100 shadow-sm space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 block">Keterbukaan Informasi</span>
                <h4 class="font-extrabold text-slate-900 text-sm">Repositori PPID Desa</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Dapatkan salinan dokumen publik, peraturan desa (Perdes), dan laporan APBDes secara terbuka.
                </p>
                <a href="{{ route('public.ppid.documents') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 hover:text-emerald-700 pt-1">
                    <span>Akses Dokumen Publik (DIP)</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </aside>
    </div>
</section>
@endsection
