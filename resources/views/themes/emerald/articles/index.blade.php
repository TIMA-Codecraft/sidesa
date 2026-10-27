@extends(theme_layout())

@section('title', ($currentCategory ? $currentCategory->name . ' - ' : '') . 'Kabar & Berita - ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju'))

@section('content')
@component('themes.emerald.partials.page-hero', [
    'breadcrumbs' => array_values(array_filter([
        ['label' => 'Beranda', 'url' => route('home'), 'icon' => '🏡'],
        ['label' => 'Warta & Kabar Desa', 'url' => $currentCategory ? route('articles.index') : null],
        $currentCategory ? ['label' => $currentCategory->name, 'muted' => true] : null,
    ])),
    'eyebrow' => 'Warta Resmi & Dokumentasi Desa ' . village_name(withPrefix: false),
    'eyebrowIcon' => '📰',
    'title' => $currentCategory
        ? 'Kategori: ' . $currentCategory->name
        : ($search ? 'Hasil Pencarian: "' . $search . '"' : 'Kabar & Warta Terkini Desa'),
    'description' => 'Informasi kegiatan, pengumuman publik, serta transparansi pembangunan desa yang asri, mandiri, dan berkelanjutan.',
])
    <div class="pt-4 max-w-xl">
        <form action="{{ route('articles.index') }}" method="GET" class="flex gap-2 bg-white/10 backdrop-blur-md border border-white/20 p-1.5 rounded-2xl shadow-inner">
            <div class="pl-3 flex items-center text-emerald-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input
                type="text"
                name="q"
                value="{{ $search }}"
                placeholder="Cari kabar, pengumuman, atau artikel..."
                class="w-full bg-transparent border-0 text-white placeholder-emerald-200/70 text-xs sm:text-sm focus:outline-none focus:ring-0"
            >
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 text-slate-950 font-bold text-xs hover:opacity-90 transition shadow-md">
                Cari
            </button>
        </form>
    </div>
@endcomponent

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 text-xs font-semibold scrollbar-thin">
        <a href="{{ route('articles.index') }}" class="px-4 py-2.5 rounded-full whitespace-nowrap transition {{ empty($currentCategory) ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md shadow-emerald-600/20' : 'bg-white text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200' }}">
            Semua Kabar
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('articles.category', $cat->slug) }}" class="px-4 py-2.5 rounded-full whitespace-nowrap transition {{ optional($currentCategory)->id === $cat->id ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md shadow-emerald-600/20' : 'bg-white text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200' }}">
                {{ $cat->name }} ({{ $cat->contents_count }})
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($articles as $article)
            @include('themes.emerald.partials.article-card', ['article' => $article])
        @empty
            @include('themes.emerald.partials.empty-state', [
                'icon' => '📰',
                'title' => 'Tidak ada kabar yang ditemukan',
                'description' => 'Coba gunakan kata kunci pencarian lain atau pilih kategori berbeda.',
                'actionUrl' => route('articles.index'),
                'actionLabel' => 'Tampilkan Semua Berita',
                'class' => 'col-span-full',
            ])
        @endforelse
    </div>

    @if($articles->hasPages())
        <div class="mt-12">
            {{ $articles->links() }}
        </div>
    @endif
</div>
@endsection
