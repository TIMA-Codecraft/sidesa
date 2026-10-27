@php
    $tone = $tone ?? 'info';
    $tones = [
        'info' => ['bg-emerald-50 border-emerald-100 text-emerald-950', 'ℹ️'],
        'success' => ['bg-emerald-50 border-emerald-200 text-emerald-800', '✅'],
        'warning' => ['bg-amber-50 border-amber-200 text-amber-800', '⚠️'],
        'danger' => ['bg-rose-50 border-rose-200 text-rose-700', '⚠️'],
    ];
    [$toneClass, $defaultIcon] = $tones[$tone] ?? $tones['info'];
    $classes = $class ?? '';
@endphp
<div class="{{ $classes }} p-4 {{ $toneClass }} border rounded-2xl text-xs space-y-1">
    @if (! empty($title))
        <div class="font-bold flex items-center space-x-1.5">
            <span>{{ $icon ?? $defaultIcon }}</span>
            <span>{{ $title }}</span>
        </div>
    @endif

    @if (! empty($message))
        <p class="{{ $title ? 'text-[11px]' : '' }} leading-relaxed flex items-start gap-1.5">
            @unless ($title)
                <span class="shrink-0">{{ $icon ?? $defaultIcon }}</span>
            @endunless
            <span>{{ $message }}</span>
        </p>
    @endif

    @if (! empty($items))
        <ul class="list-disc list-inside space-y-0.5 text-[11px] pt-1">
            @foreach ($items as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @endif
</div>
