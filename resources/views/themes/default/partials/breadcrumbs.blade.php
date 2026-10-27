@php
    $items = $items ?? [];
    $variant = $variant ?? 'light';
    $class = $class ?? '';
    $isHero = $variant === 'hero';
@endphp

@if (count($items) > 0)
    <nav class="flex items-center gap-2 text-xs overflow-x-auto {{ $isHero ? 'text-sky-200' : 'text-slate-500' }} {{ $class }}" aria-label="Breadcrumb">
        @foreach ($items as $item)
            @unless ($loop->first)
                <span class="{{ $isHero ? 'text-sky-400/60' : 'text-slate-300' }}">/</span>
            @endunless

            @if (! empty($item['url']))
                <a href="{{ $item['url'] }}" class="whitespace-nowrap transition {{ $isHero ? 'hover:text-white' : 'hover:text-sky-600' }}">
                    {{ $item['label'] }}
                </a>
            @else
                <span class="truncate font-medium {{ $isHero ? 'text-white' : 'text-slate-800' }}">
                    {{ $item['label'] }}
                </span>
            @endif
        @endforeach
    </nav>
@endif
