@php
    $container = $container ?? 'max-w-7xl';
    $contentWidth = $contentWidth ?? 'max-w-4xl';
    $padding = $padding ?? 'py-12 sm:py-16';
    $titleClass = $titleClass ?? 'text-3xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight drop-shadow-sm';
    $glow = $glow ?? 'emerald';
    $badgeClass = $badgeClass ?? ($glow === 'amber'
        ? 'bg-amber-500/20 border-amber-400/30 text-amber-200'
        : 'bg-emerald-500/20 border-emerald-400/30 text-emerald-200');
    $glowClass = $glow === 'amber' ? 'bg-amber-500/10' : 'bg-emerald-400/10';
@endphp
<section class="relative px-4 sm:px-6 lg:px-8 pt-6 pb-4">
    <div class="{{ $container }} mx-auto">
        <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-emerald-950/20 bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-950 {{ $padding }} px-6 sm:px-12 text-white">
            <div class="absolute inset-0 bg-cover bg-center opacity-15 mix-blend-overlay pointer-events-none" style="background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1600&q=80');"></div>
            <div class="absolute -right-10 -bottom-10 w-80 h-80 {{ $glowClass }} rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-10 -top-10 w-72 h-72 bg-teal-300/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 {{ $contentWidth }} space-y-4">
                @if (! empty($breadcrumbs))
                    @include('themes.emerald.partials.breadcrumbs', ['items' => $breadcrumbs])
                @endif

                @if (! empty($eyebrow))
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border text-xs font-bold backdrop-blur-md {{ $badgeClass }}">
                        @if (! empty($eyebrowIcon))
                            <span>{{ $eyebrowIcon }}</span>
                        @endif
                        <span>{{ $eyebrow }}</span>
                    </div>
                @endif

                <h1 class="{{ $titleClass }}">{{ $title }}</h1>

                @if (! empty($description))
                    <p class="text-sm sm:text-base lg:text-lg text-emerald-100/90 leading-relaxed font-normal max-w-3xl">{{ $description }}</p>
                @endif

                @isset($slot)
                    {{ $slot }}
                @endisset
            </div>
        </div>
    </div>
</section>
