@extends(theme_layout())

@section('title', ($currentCategory ? $currentCategory->name . ' - ' : '') . 'Kabar & Berita - ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju'))

@section('content')
@php
    $heroTitle = $currentCategory
        ? 'Kategori: ' . $currentCategory->name
        : ($search ? 'Hasil Pencarian: "' . $search . '"' : 'Kabar & Informasi Terkini');
@endphp

@component('themes.default.partials.page-hero', [
    'eyebrow' => 'Warta & Dokumentasi Desa',
    'eyebrowIcon' => '📰',
    'title' => $heroTitle,
    'description' => 'Transparansi kegiatan, pengumuman publik, dan kabar terkini seputar pembangunan dan kehidupan masyarakat ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju') . '.',
])
    <div class="mt-8 max-w-xl">
        <form action="{{ route('articles.index') }}" method="GET" class="flex gap-2">
            <input
                type="text"
                name="q"
                value="{{ $search }}"
                placeholder="Cari kabar, pengumuman, atau artikel..."
                class="w-full px-4 py-3 rounded-xl bg-white/10 text-white placeholder-sky-200 border border-white/20 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 backdrop-blur-md"
            >
            <button type="submit" class="px-6 py-3 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-semibold text-sm transition shadow-md">
                Cari
            </button>
        </form>
    </div>
@endcomponent

<!-- Content & Category Filter -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Category Pills -->
    <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 text-xs font-semibold scrollbar-thin">
        <a href="{{ route('articles.index') }}" class="px-4 py-2 rounded-full whitespace-nowrap transition {{ empty($currentCategory) ? 'bg-sky-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
            Semua Kabar
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('articles.category', $cat->slug) }}" class="px-4 py-2 rounded-full whitespace-nowrap transition {{ optional($currentCategory)->id === $cat->id ? 'bg-sky-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                {{ $cat->name }} ({{ $cat->contents_count }})
            </a>
        @endforeach
    </div>

    <!-- Articles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($articles as $article)
            @include('themes.default.partials.article-card', [
                'article' => $article,
                'showReadingTime' => true,
            ])
        @empty
            @include('themes.default.partials.empty-state', [
                'icon' => '📰',
                'title' => 'Tidak ada kabar yang ditemukan',
                'description' => 'Coba gunakan kata kunci pencarian lain atau pilih kategori berbeda.',
                'actionUrl' => route('articles.index'),
                'actionLabel' => 'Tampilkan Semua Berita',
                'class' => 'col-span-full',
            ])
        @endforelse
    </div>

    <!-- Pagination -->
    @if($articles->hasPages())
        <div class="mt-12">
            {{ $articles->links() }}
        </div>
    @endif
</div>
@endsection
