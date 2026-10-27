@php
    $compact = $compact ?? false;
    $classes = $class ?? '';
@endphp
<div class="{{ $classes }} {{ $compact ? 'p-8' : 'py-16 px-6' }} text-center text-slate-400 bg-white rounded-3xl border border-dashed border-emerald-200">
    @if (! empty($icon))
        <div class="text-5xl mb-3">{{ $icon }}</div>
    @endif
    <h3 class="text-base font-bold text-slate-700">{{ $title }}</h3>
    @if (! empty($description))
        <p class="text-xs text-slate-400 mt-1">{{ $description }}</p>
    @endif
    @if (! empty($actionUrl))
        <div class="mt-4">
            <a href="{{ $actionUrl }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-sm">
                {{ $actionLabel ?? 'Lihat Semua' }}
            </a>
        </div>
    @endif
</div>
