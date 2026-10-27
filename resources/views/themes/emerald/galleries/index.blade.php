@extends(theme_layout())

@section('title', 'Album Galeri & Dokumentasi Kegiatan - ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju'))

@section('content')
@component('themes.emerald.partials.page-hero', [
    'breadcrumbs' => [
        ['label' => 'Beranda', 'url' => route('home'), 'icon' => '🏡'],
        ['label' => 'Galeri & Potret Desa'],
    ],
    'eyebrow' => 'Dokumentasi Visual & Potret Kehidupan Desa',
    'eyebrowIcon' => '📸',
    'title' => 'Galeri Foto Desa & Potret Kegiatan',
    'description' => 'Koleksi album dokumentasi foto pembangunan, kegiatan sosial kemasyarakatan, adat budaya, serta keindahan potensi alam ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju') . '.',
])
@endcomponent

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($galleries as $album)
            @include('themes.emerald.partials.gallery-card', ['album' => $album])
        @empty
            @include('themes.emerald.partials.empty-state', [
                'icon' => '🖼️',
                'title' => 'Belum ada album galeri foto',
                'description' => 'Dokumentasi kegiatan dan album foto desa akan segera ditambahkan di sini.',
                'class' => 'col-span-full',
            ])
        @endforelse
    </div>

    @if($galleries->hasPages())
        <div class="mt-12">
            {{ $galleries->links() }}
        </div>
    @endif
</div>
@endsection
