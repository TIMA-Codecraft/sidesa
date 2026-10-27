@extends(theme_layout())

@section('title', $article->title . ' - ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju'))

@section('content')
@component('themes.emerald.partials.page-hero', [
    'breadcrumbs' => [
        ['label' => 'Beranda', 'url' => route('home'), 'icon' => '🏡'],
        ['label' => 'Kabar Desa', 'url' => route('articles.index')],
        ['label' => $article->title, 'truncate' => true],
    ],
    'title' => $article->title,
    'titleClass' => 'text-2xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight drop-shadow-sm',
])
    @if($article->categories->isNotEmpty())
        <div class="flex flex-wrap gap-2 pt-1">
            @foreach($article->categories as $category)
                <a href="{{ route('articles.category', $category->slug) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-200 border border-emerald-400/30 backdrop-blur-md hover:bg-emerald-500/30 transition">
                    🏷️ {{ $category->name }}
                </a>
            @endforeach
        </div>
    @endif

    <div class="pt-4 flex flex-wrap items-center justify-between gap-4 text-xs text-emerald-200/80 border-t border-white/10">
        <div class="flex flex-wrap items-center gap-4">
            <span class="flex items-center gap-1.5 font-medium text-emerald-200">
                <span>✍️</span>
                <span>Oleh: <strong>{{ $article->author->name ?? 'Aparatur Desa' }}</strong></span>
            </span>
            <span class="text-emerald-500">&bull;</span>
            <span class="flex items-center gap-1.5 text-emerald-200">
                <span>📅</span>
                <span>{{ optional($article->published_at)->translatedFormat('l, d F Y') }}</span>
            </span>
            <span class="text-emerald-500">&bull;</span>
            <span class="flex items-center gap-1.5 text-emerald-200">
                <span>⏱️</span>
                <span>{{ $article->reading_time }} menit baca</span>
            </span>
            <span class="text-emerald-500">&bull;</span>
            <span class="flex items-center gap-1.5 text-emerald-200">
                <span>👁️</span>
                <span>{{ number_format($article->view_count) }} kali dibaca</span>
            </span>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-md border border-white/20 transition shadow-sm cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Artikel</span>
            </button>
        </div>
    </div>
@endcomponent

<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if($article->cover_image)
        <div class="mb-8 rounded-3xl overflow-hidden shadow-sm border border-emerald-100">
            <img
                src="{{ $article->cover_image }}"
                alt="{{ $article->title }}"
                class="w-full max-h-[500px] object-cover"
            >
        </div>
    @endif

    @if($article->summary)
        <div class="text-base sm:text-lg font-medium text-slate-700 leading-relaxed mb-8 p-6 bg-emerald-50/60 rounded-3xl border-l-4 border-emerald-500">
            {{ $article->summary }}
        </div>
    @endif

    <div class="prose prose-slate prose-emerald max-w-none text-slate-700 leading-relaxed space-y-4 text-sm sm:text-base">
        {!! $article->rendered_body !!}
    </div>

    <div class="mt-12 pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
        <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-emerald-700 hover:text-emerald-800 transition">
            <span>&larr;</span>
            <span>Kembali ke Semua Kabar Desa</span>
        </a>
    </div>
</article>

@if($relatedArticles->isNotEmpty())
    <section class="bg-emerald-50/40 border-t border-emerald-100/60 py-14 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            @include('themes.emerald.partials.section-header', [
                'eyebrow' => 'Artikel Terkait',
                'title' => 'Kabar Desa Terkait Lainnya',
                'actionUrl' => route('articles.index'),
                'actionLabel' => 'Lihat Semua',
            ])

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                @foreach($relatedArticles as $related)
                    <div class="bg-white p-6 rounded-3xl border border-emerald-100 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                        <div>
                            <div class="text-[11px] text-slate-400 mb-2">
                                📅 {{ optional($related->published_at)->translatedFormat('d M Y') }}
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm group-hover:text-emerald-700 line-clamp-2 transition mb-2">
                                <a href="{{ route('articles.show', $related->slug) }}">
                                    {{ $related->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                {{ $related->summary }}
                            </p>
                        </div>
                        <div class="pt-4 mt-4 border-t border-slate-100">
                            <a href="{{ route('articles.show', $related->slug) }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 inline-flex items-center gap-1">
                                <span>Baca &rarr;</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection
