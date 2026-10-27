@php
    $compact = $compact ?? false;
    $showSummary = $showSummary ?? true;
    $showReadingTime = $showReadingTime ?? true;
    $showAuthor = $showAuthor ?? true;
    $readLabel = $readLabel ?? 'Baca Selengkapnya';
    $cover = $article->cover_image ?: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80';
    $imageHeight = $compact ? 'h-44' : 'h-52';
    $bodyPadding = $compact ? 'p-5' : 'p-6';
@endphp
<article class="bg-white rounded-3xl border border-emerald-100 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
    <div>
        <div class="relative {{ $imageHeight }} bg-slate-100 overflow-hidden">
            <img
                src="{{ $cover }}"
                alt="{{ $article->title }}"
                class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                loading="lazy"
            >
            @if (! $compact && $article->categories->isNotEmpty())
                <div class="absolute top-3 left-3 flex flex-wrap gap-1">
                    @foreach ($article->categories->take(2) as $category)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/95 text-emerald-800 backdrop-blur-md shadow-sm border border-emerald-100">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="{{ $bodyPadding }}">
            <div class="flex items-center space-x-2 text-[11px] text-slate-400 {{ $compact ? 'mb-1' : 'mb-2' }}">
                <span>📅 {{ optional($article->published_at)->translatedFormat('d M Y') }}</span>
                @if ($showReadingTime)
                    <span>&bull;</span>
                    <span>⏱️ {{ $article->reading_time }} mnt baca</span>
                @endif
            </div>

            <h2 class="text-base sm:text-lg font-bold text-slate-900 leading-snug line-clamp-2 group-hover:text-emerald-700 transition">
                <a href="{{ route('articles.show', $article->slug) }}">
                    {{ $article->title }}
                </a>
            </h2>

            @if ($showSummary && $article->summary)
                <p class="text-xs text-slate-600 mt-2.5 line-clamp-3 leading-relaxed">
                    {{ $article->summary }}
                </p>
            @endif
        </div>
    </div>

    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs text-slate-500">
        @if ($showAuthor)
            <span class="font-medium text-slate-700">✍️ {{ $article->author->name ?? 'Aparatur Desa' }}</span>
        @else
            <span></span>
        @endif
        <a href="{{ route('articles.show', $article->slug) }}" class="font-bold text-emerald-600 hover:text-emerald-700 transition flex items-center space-x-1">
            <span>{{ $readLabel }}</span>
            <span>&rarr;</span>
        </a>
    </div>
</article>
