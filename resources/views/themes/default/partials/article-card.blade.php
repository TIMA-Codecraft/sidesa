@php
    $compact = $compact ?? false;
    $showReadingTime = $showReadingTime ?? false;
    $cover = $article->cover_image ?: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80';
@endphp

<article class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
    <div>
        @unless ($compact)
            <div class="relative h-48 bg-slate-100 overflow-hidden">
                <img
                    src="{{ $cover }}"
                    alt="{{ $article->title }}"
                    class="w-full h-full object-cover transition duration-300 hover:scale-105"
                    loading="lazy"
                >
                @if ($article->categories->isNotEmpty())
                    <div class="absolute top-3 left-3 flex flex-wrap gap-1">
                        @foreach ($article->categories->take(2) as $category)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/90 text-sky-800 backdrop-blur-md shadow-sm">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        @endunless

        <div class="p-6">
            <div class="flex flex-wrap items-center gap-2 text-[11px] text-slate-400 mb-2">
                <span>📅 {{ optional($article->published_at)->translatedFormat('d M Y') }}</span>
                @if ($showReadingTime)
                    <span>•</span>
                    <span>⏱️ {{ $article->reading_time }} mnt baca</span>
                @endif
            </div>

            <h2 class="font-bold text-slate-900 leading-snug line-clamp-2 hover:text-sky-600 transition {{ $compact ? 'text-sm' : 'text-lg' }}">
                <a href="{{ route('articles.show', $article->slug) }}">
                    {{ $article->title }}
                </a>
            </h2>

            <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                {{ $article->summary }}
            </p>
        </div>
    </div>

    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs text-slate-500">
        <span class="font-medium text-slate-700">✍️ {{ $article->author->name ?? 'Aparatur Desa' }}</span>
        <a href="{{ route('articles.show', $article->slug) }}" class="font-bold text-sky-600 hover:text-sky-700 transition inline-flex items-center gap-1">
            <span>Baca</span>
            <span>&rarr;</span>
        </a>
    </div>
</article>
