<nav class="flex flex-wrap items-center gap-2 text-xs text-emerald-300 font-medium">
    @foreach ($items as $index => $item)
        @if ($index > 0)
            <span class="text-emerald-500">/</span>
        @endif

        @if (! empty($item['url']))
            <a href="{{ $item['url'] }}" class="hover:text-white transition flex items-center gap-1">
                @if (! empty($item['icon']))
                    <span>{{ $item['icon'] }}</span>
                @endif
                <span>{{ $item['label'] }}</span>
            </a>
        @else
            <span class="{{ ($item['muted'] ?? false) ? 'text-emerald-300' : 'text-white font-semibold' }} {{ ($item['mono'] ?? false) ? 'font-mono' : '' }} {{ ($item['truncate'] ?? false) ? 'truncate max-w-xs' : '' }}">
                {{ $item['label'] }}
            </span>
        @endif
    @endforeach
</nav>
