@php
    $eyebrow = $eyebrow ?? null;
    $title = $title ?? '';
    $description = $description ?? null;
    $actionUrl = $actionUrl ?? null;
    $actionLabel = $actionLabel ?? null;
    $align = $align ?? 'left';
@endphp

<div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 {{ $align === 'center' ? 'text-center' : '' }}">
    <div class="{{ $align === 'center' ? 'mx-auto' : '' }}">
        @if ($eyebrow)
            <span class="block text-xs font-bold text-sky-600 uppercase tracking-wider mb-1">{{ $eyebrow }}</span>
        @endif
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $title }}</h2>
        @if ($description)
            <p class="text-xs text-slate-500 mt-1">{{ $description }}</p>
        @endif
    </div>

    @if ($actionUrl && $actionLabel)
        <a href="{{ $actionUrl }}" class="inline-flex items-center gap-1 text-xs font-bold text-sky-600 hover:text-sky-700 transition whitespace-nowrap">
            <span>{{ $actionLabel }}</span>
            <span>&rarr;</span>
        </a>
    @endif
</div>
