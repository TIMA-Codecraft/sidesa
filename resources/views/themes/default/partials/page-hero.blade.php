@php
    $variant = $variant ?? 'default';
    $align = $align ?? 'left';
    $container = $container ?? 'max-w-7xl';
    $titleClass = $titleClass ?? 'text-3xl sm:text-4xl font-black tracking-tight leading-tight';

    $gradients = [
        'default' => 'from-sky-900 via-sky-800 to-indigo-950',
        'emerald' => 'from-emerald-900 via-teal-900 to-slate-900',
        'amber' => 'from-amber-900 via-orange-900 to-slate-900',
        'rose' => 'from-rose-900 via-rose-800 to-slate-900',
        'slate' => 'from-slate-900 via-slate-800 to-indigo-950',
    ];

    $badgeTones = [
        'default' => 'bg-sky-500/20 text-sky-200 border-sky-400/30',
        'emerald' => 'bg-emerald-500/20 text-emerald-200 border-emerald-400/30',
        'amber' => 'bg-amber-500/20 text-amber-200 border-amber-400/30',
        'rose' => 'bg-rose-500/20 text-rose-200 border-rose-400/30',
        'slate' => 'bg-white/10 text-white border-white/20',
    ];

    $bodyTones = [
        'default' => 'text-sky-100',
        'emerald' => 'text-emerald-100',
        'amber' => 'text-amber-100',
        'rose' => 'text-rose-100',
        'slate' => 'text-slate-200',
    ];

    $gradient = $gradients[$variant] ?? $gradients['default'];
    $badgeTone = $badgeTones[$variant] ?? $badgeTones['default'];
    $bodyTone = $bodyTones[$variant] ?? $bodyTones['default'];
@endphp

<section class="bg-gradient-to-r {{ $gradient }} text-white py-14 px-4 sm:px-6 lg:px-8">
    <div class="{{ $container }} mx-auto {{ $align === 'center' ? 'text-center' : '' }}">
        @if (! empty($breadcrumbs))
            @include('themes.default.partials.breadcrumbs', [
                'items' => $breadcrumbs,
                'variant' => 'hero',
                'class' => 'mb-4',
            ])
        @endif

        @if (! empty($eyebrow))
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold border backdrop-blur-sm {{ $badgeTone }} {{ $align === 'center' ? 'mx-auto' : '' }}">
                @if (! empty($eyebrowIcon))
                    <span>{{ $eyebrowIcon }}</span>
                @endif
                <span>{{ $eyebrow }}</span>
            </span>
        @endif

        <h1 class="mt-3 {{ $titleClass }}">
            {{ $title }}
        </h1>

        @if (! empty($description))
            <p class="mt-2 text-sm sm:text-base {{ $bodyTone }} max-w-2xl leading-relaxed {{ $align === 'center' ? 'mx-auto' : '' }}">
                {{ $description }}
            </p>
        @endif

        @isset($slot)
            {{ $slot }}
        @endisset
    </div>
</section>
