@php
    $tone = $tone ?? 'info';
    $icon = $icon ?? null;
    $title = $title ?? null;
    $message = $message ?? null;
    $items = $items ?? [];

    $tones = [
        'info' => [
            'wrapper' => 'bg-sky-50 border-sky-200 text-sky-900',
            'body' => 'text-sky-700',
            'icon' => 'ℹ️',
        ],
        'success' => [
            'wrapper' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
            'body' => 'text-emerald-700',
            'icon' => '✅',
        ],
        'warning' => [
            'wrapper' => 'bg-amber-50 border-amber-200 text-amber-800',
            'body' => 'text-amber-700',
            'icon' => '⚠️',
        ],
        'danger' => [
            'wrapper' => 'bg-rose-50 border-rose-200 text-rose-700',
            'body' => 'text-rose-700',
            'icon' => '⛔',
        ],
    ];

    $palette = $tones[$tone] ?? $tones['info'];
    $icon = $icon ?? $palette['icon'];
@endphp

<div class="p-4 rounded-2xl border text-xs space-y-1 {{ $palette['wrapper'] }}">
    @if ($title)
        <div class="font-bold flex items-center gap-1.5">
            <span>{{ $icon }}</span>
            <span>{{ $title }}</span>
        </div>
    @endif

    @if ($message)
        <p class="{{ $title ? $palette['body'] : 'flex items-center gap-2' }} leading-relaxed">
            @unless ($title)
                <span>{{ $icon }}</span>
            @endunless
            <span>{{ $message }}</span>
        </p>
    @endif

    @if (count($items) > 0)
        <ul class="list-disc list-inside space-y-0.5 text-[11px] pt-1 {{ $palette['body'] }}">
            @foreach ($items as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @endif
</div>
