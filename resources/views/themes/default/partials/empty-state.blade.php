@php
    $icon = $icon ?? '📭';
    $title = $title ?? 'Belum ada data';
    $description = $description ?? null;
    $actionUrl = $actionUrl ?? null;
    $actionLabel = $actionLabel ?? 'Tampilkan Semua';
    $compact = $compact ?? false;
    $class = $class ?? '';
@endphp

<div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 text-center px-6 {{ $compact ? 'py-10' : 'py-16' }} {{ $class }}">
    <div class="text-4xl mb-3">{{ $icon }}</div>
    <h3 class="text-base font-bold text-slate-700">{{ $title }}</h3>
    @if ($description)
        <p class="text-xs text-slate-400 mt-1">{{ $description }}</p>
    @endif
    @if ($actionUrl)
        <a href="{{ $actionUrl }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-sky-600 text-white hover:bg-sky-500 transition mt-4">
            {{ $actionLabel }}
        </a>
    @endif
</div>
