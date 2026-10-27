@php
    $tone = $tone ?? 'sky';
    $icon = $icon ?? '📊';
    $label = $label ?? '';
    $value = $value ?? '0';
    $meta = $meta ?? null;

    $tones = [
        'sky' => 'bg-sky-50 text-sky-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'rose' => 'bg-rose-50 text-rose-600',
        'indigo' => 'bg-indigo-50 text-indigo-600',
        'slate' => 'bg-slate-100 text-slate-600',
    ];

    $toneClass = $tones[$tone] ?? $tones['sky'];
@endphp

<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shrink-0 {{ $toneClass }}">
        {{ $icon }}
    </div>
    <div class="min-w-0">
        <span class="text-2xl font-black text-slate-800">{{ $value }}</span>
        <p class="text-xs text-slate-500 font-medium">{{ $label }}</p>
        @if ($meta)
            <p class="text-[11px] text-slate-400 mt-0.5">{{ $meta }}</p>
        @endif
    </div>
</div>
