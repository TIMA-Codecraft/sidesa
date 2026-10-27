@php
    $photoCount = $photoCount ?? ($album->photos_count ?? $album->photos->count());
@endphp
<div class="bg-white rounded-3xl border border-emerald-100 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 group flex flex-col justify-between">
    <div>
        <a href="{{ route('galleries.show', $album->slug) }}" class="block relative h-64 bg-slate-100 overflow-hidden group-hover:opacity-95 transition">
            <img
                src="{{ $album->cover_image }}"
                alt="{{ $album->title }}"
                class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                loading="lazy"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-80 group-hover:opacity-60 transition"></div>

            <div class="absolute top-3 right-3">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-900/75 text-white backdrop-blur-sm border border-white/20 shadow-sm space-x-1.5">
                    <span>🖼️</span>
                    <span>{{ $photoCount }} Foto</span>
                </span>
            </div>

            <div class="absolute bottom-3 left-3 text-xs text-white/90 font-medium flex items-center space-x-1">
                <span>📅</span>
                <span>{{ optional($album->published_at)->translatedFormat('d M Y') }}</span>
            </div>
        </a>

        <div class="p-6">
            <h3 class="font-bold text-slate-800 text-lg leading-snug mb-2 group-hover:text-emerald-700 transition">
                <a href="{{ route('galleries.show', $album->slug) }}">
                    {{ $album->title }}
                </a>
            </h3>
            <p class="text-xs sm:text-sm text-slate-600 line-clamp-2 leading-relaxed">
                {{ $album->summary }}
            </p>
        </div>
    </div>

    <div class="px-6 pb-6 pt-0">
        <a
            href="{{ route('galleries.show', $album->slug) }}"
            class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white transition group-hover:bg-emerald-600 group-hover:text-white border border-emerald-200 group-hover:border-transparent space-x-2"
        >
            <span>Buka Album</span>
            <span>&rarr;</span>
        </a>
    </div>
</div>
