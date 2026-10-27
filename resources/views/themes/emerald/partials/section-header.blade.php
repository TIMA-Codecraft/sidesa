@php
    $align = $align ?? 'left';
@endphp
<div class="flex flex-wrap items-end justify-between gap-4 {{ $align === 'center' ? 'text-center justify-center' : '' }}">
    <div>
        @if (! empty($eyebrow))
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 block mb-1">{{ $eyebrow }}</span>
        @endif
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ $title }}</h2>
        @if (! empty($description))
            <p class="text-xs text-slate-500 mt-1">{{ $description }}</p>
        @endif
    </div>

    @if (! empty($actionUrl))
        <a href="{{ $actionUrl }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 group">
            <span>{{ $actionLabel ?? 'Lihat Semua' }}</span>
            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>
    @endif
</div>
