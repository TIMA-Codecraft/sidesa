@extends(theme_layout())

@section('title', $article->title . ' - ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju'))

@section('content')
<!-- Breadcrumbs -->
<div class="bg-slate-100 border-b border-slate-200 py-3.5 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        @include('themes.default.partials.breadcrumbs', [
            'items' => [
                ['label' => 'Beranda', 'url' => route('home')],
                ['label' => 'Kabar Desa', 'url' => route('articles.index')],
                ['label' => $article->title],
            ],
        ])
    </div>
</div>

<!-- Article Detail -->
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Categories Badges -->
    <div class="flex flex-wrap gap-2 mb-4">
        @foreach($article->categories as $category)
            <a href="{{ route('articles.category', $category->slug) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 hover:bg-sky-100 transition border border-sky-200">
                🏷️ {{ $category->name }}
            </a>
        @endforeach
    </div>

    <!-- Title -->
    <h1 class="text-2xl sm:text-4xl font-black text-slate-900 leading-tight tracking-tight mb-6">
        {{ $article->title }}
    </h1>

    <!-- Meta Info Bar -->
    <div class="flex flex-wrap items-center justify-between gap-4 py-4 border-y border-slate-200 text-xs text-slate-500 mb-8">
        <div class="flex items-center space-x-4">
            <span class="font-medium text-slate-700">✍️ Oleh: <strong>{{ $article->author->name ?? 'Aparatur Desa' }}</strong></span>
            <span>📅 {{ optional($article->published_at)->translatedFormat('l, d F Y - H:i') }} {{ timezone_label() }}</span>
        </div>
        <div class="flex items-center space-x-4">
            <span>⏱️ {{ $article->reading_time }} menit baca</span>
            <span>👁️ {{ number_format($article->view_count) }} kali dibaca</span>
        </div>
    </div>

    <!-- Cover Image -->
    @if($article->cover_image)
        <div class="mb-10 rounded-2xl overflow-hidden shadow-sm border border-slate-200">
            <img 
                src="{{ $article->cover_image }}" 
                alt="{{ $article->title }}" 
                class="w-full max-h-[480px] object-cover"
            >
        </div>
    @endif

    <!-- Summary Lead -->
    @if($article->summary)
        <div class="text-base sm:text-lg font-medium text-slate-700 leading-relaxed mb-8 p-5 bg-sky-50/60 rounded-2xl border-l-4 border-sky-600">
            {{ $article->summary }}
        </div>
    @endif

    <!-- Content Body -->
    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed space-y-4 text-sm sm:text-base">
        {!! $article->rendered_body !!}
    </div>

    <!-- Share & Navigation Footer -->
    <div class="mt-12 pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
        <a href="{{ route('articles.index') }}" class="inline-flex items-center space-x-2 text-xs font-semibold text-sky-600 hover:text-sky-700 transition">
            <span>&larr;</span>
            <span>Kembali ke Semua Kabar Desa</span>
        </a>

        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 rounded-xl text-xs font-medium bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                🖨️ Cetak Artikel
            </button>
        </div>
    </div>
</article>

<!-- Related Articles Section -->
@if($relatedArticles->isNotEmpty())
    <section class="bg-slate-50 border-t border-slate-200 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-xl font-bold text-slate-900 mb-6">Kabar Terkait Lainnya</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedArticles as $related)
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition">
                        <div class="text-[11px] text-slate-400 mb-2">
                            📅 {{ optional($related->published_at)->translatedFormat('d M Y') }}
                        </div>
                        <h3 class="font-bold text-slate-800 text-sm hover:text-sky-600 line-clamp-2 transition mb-2">
                            <a href="{{ route('articles.show', $related->slug) }}">
                                {{ $related->title }}
                            </a>
                        </h3>
                        <p class="text-xs text-slate-500 line-clamp-2">
                            {{ $related->summary }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection
