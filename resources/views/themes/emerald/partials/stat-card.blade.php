@php
    $tone = $tone ?? 'emerald';
    $tones = [
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'teal' => 'bg-teal-50 text-teal-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'slate' => 'bg-slate-100 text-slate-600',
    ];
    $toneClass = $tones[$tone] ?? $tones['emerald'];
@endphp
<div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center space-x-4">
    <div class="w-12 h-12 rounded-2xl {{ $toneClass }} flex items-center justify-center text-2xl font-bold">
        {{ $icon }}
    </div>
    <div>
        <span class="text-2xl font-black text-slate-800">{{ $value }}</span>
        <p class="text-xs text-slate-500 font-medium">{{ $label }}</p>
    </div>
</div>
