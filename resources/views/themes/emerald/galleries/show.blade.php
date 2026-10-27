@extends(theme_layout())

@section('title', $gallery->title . ' - Galeri ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju'))

@section('content')
@component('themes.emerald.partials.page-hero', [
    'breadcrumbs' => [
        ['label' => 'Beranda', 'url' => route('home'), 'icon' => '🏡'],
        ['label' => 'Galeri Foto', 'url' => route('galleries.index')],
        ['label' => $gallery->title, 'truncate' => true],
    ],
    'eyebrow' => 'Album Dokumentasi Kegiatan Desa',
    'eyebrowIcon' => '📷',
    'title' => $gallery->title,
    'titleClass' => 'text-2xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight drop-shadow-sm',
])
    <div class="pt-4 flex flex-wrap items-center gap-4 text-xs text-emerald-200/80 border-t border-white/10 font-medium">
        <span class="flex items-center gap-1.5 text-emerald-200">
            <span>📅</span>
            <span>{{ optional($gallery->published_at)->translatedFormat('d F Y') }}</span>
        </span>
        <span class="text-emerald-500">&bull;</span>
        <span class="flex items-center gap-1.5 text-emerald-200">
            <span>🖼️</span>
            <span>{{ $gallery->photos->count() }} Foto Dokumentasi</span>
        </span>
        @if($gallery->description)
            <span class="text-emerald-500">&bull;</span>
            <span class="text-emerald-100/90">{{ $gallery->description }}</span>
        @endif
    </div>
@endcomponent

<div
    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
    x-data="{
        open: false,
        activePhoto: 0,
        photos: {{ Js::from($gallery->photos->map(fn($p) => ['url' => $p->image_url, 'caption' => $p->caption ?? $gallery->title])) }},
        openModal(index) {
            this.activePhoto = index;
            this.open = true;
            document.body.classList.add('overflow-hidden');
        },
        closeModal() {
            this.open = false;
            document.body.classList.remove('overflow-hidden');
        },
        next() {
            if (this.photos.length > 0) {
                this.activePhoto = (this.activePhoto + 1) % this.photos.length;
            }
        },
        prev() {
            if (this.photos.length > 0) {
                this.activePhoto = (this.activePhoto - 1 + this.photos.length) % this.photos.length;
            }
        }
    }"
    @keydown.escape.window="closeModal()"
    @keydown.right.window="open && next()"
    @keydown.left.window="open && prev()"
>
    <div class="space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-200">
            <h2 class="text-lg font-bold text-slate-800 flex items-center space-x-2">
                <span>📸</span>
                <span>Daftar Foto dalam Album</span>
                <span class="text-xs font-normal text-slate-500">({{ $gallery->photos->count() }} Foto)</span>
            </h2>
            <a href="{{ route('galleries.index') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-900 transition flex items-center space-x-1">
                <span>&larr;</span>
                <span>Kembali ke Semua Album</span>
            </a>
        </div>

        @if($gallery->photos->isEmpty())
            @include('themes.emerald.partials.empty-state', [
                'icon' => '🖼️',
                'title' => 'Belum ada foto dalam album ini',
                'description' => 'Foto-foto dokumentasi untuk kegiatan ini sedang dipersiapkan.',
            ])
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($gallery->photos as $index => $photo)
                    <div
                        @click="openModal({{ $index }})"
                        class="group cursor-pointer bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between"
                    >
                        <div class="relative aspect-video sm:aspect-square bg-slate-100 overflow-hidden">
                            <img
                                src="{{ $photo->image_url }}"
                                alt="{{ $photo->caption ?? $gallery->title }}"
                                class="w-full h-full object-cover transition duration-300 group-hover:scale-105"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                <span class="p-3 bg-white/90 text-slate-800 rounded-full shadow-lg transform translate-y-2 group-hover:translate-y-0 transition duration-200 text-sm">
                                    🔍 Perbesar
                                </span>
                            </div>
                        </div>

                        @if($photo->caption)
                            <div class="p-3.5 bg-white border-t border-slate-100">
                                <p class="text-xs text-slate-700 line-clamp-2 leading-relaxed">
                                    {{ $photo->caption }}
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-black/90 backdrop-blur-md transition-opacity"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        role="dialog"
        aria-modal="true"
    >
        <button
            @click="closeModal()"
            class="absolute top-4 right-4 z-50 text-white/80 hover:text-white p-2 rounded-full bg-white/10 hover:bg-white/20 transition focus:outline-none"
            title="Tutup (Esc)"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="absolute top-4 left-4 z-50 px-3 py-1 rounded-full bg-white/10 text-white text-xs font-semibold backdrop-blur-sm border border-white/20">
            Foto <span x-text="activePhoto + 1"></span> dari <span x-text="photos.length"></span>
        </div>

        <button
            x-show="photos.length > 1"
            @click.stop="prev()"
            class="absolute left-4 top-1/2 -translate-y-1/2 z-50 text-white/80 hover:text-white p-3 rounded-full bg-white/10 hover:bg-white/20 transition focus:outline-none"
            title="Sebelumnya (&larr;)"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <button
            x-show="photos.length > 1"
            @click.stop="next()"
            class="absolute right-4 top-1/2 -translate-y-1/2 z-50 text-white/80 hover:text-white p-3 rounded-full bg-white/10 hover:bg-white/20 transition focus:outline-none"
            title="Berikutnya (&rarr;)"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <div class="max-w-5xl max-h-[85vh] flex flex-col items-center justify-center" @click.outside="closeModal()">
            <template x-if="photos.length > 0">
                <div class="flex flex-col items-center">
                    <img
                        :src="photos[activePhoto]?.url"
                        :alt="photos[activePhoto]?.caption"
                        class="max-h-[70vh] max-w-full object-contain rounded-xl shadow-2xl transition duration-300"
                    >
                    <div class="mt-4 px-4 py-2 bg-slate-900/80 rounded-xl text-center max-w-2xl border border-white/10">
                        <p class="text-sm font-medium text-white" x-text="photos[activePhoto]?.caption"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>

    @if(isset($recentGalleries) && $recentGalleries->isNotEmpty())
        <div class="mt-16 pt-10 border-t border-slate-200">
            <h3 class="text-xl font-bold text-slate-800 mb-6">
                Album Galeri Lainnya
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($recentGalleries as $item)
                    <a href="{{ route('galleries.show', $item->slug) }}" class="group bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition">
                        <div class="relative h-40 bg-slate-100 overflow-hidden">
                            <img src="{{ $item->cover_image }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                            <span class="absolute bottom-2 right-2 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-900/80 text-white">
                                {{ $item->photos_count }} Foto
                            </span>
                        </div>
                        <div class="p-4">
                            <h4 class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 transition line-clamp-2">
                                {{ $item->title }}
                            </h4>
                            <span class="text-[10px] text-slate-400 mt-1 block">
                                {{ optional($item->published_at)->translatedFormat('d M Y') }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
