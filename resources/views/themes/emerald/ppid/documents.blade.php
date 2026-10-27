@extends(theme_layout())

@section('title', 'Daftar Dokumen Informasi Publik (DIP) - PPID Desa ' . village_name(withPrefix: false))

@section('content')
@component('themes.emerald.partials.page-hero', [
    'breadcrumbs' => [
        ['label' => 'Beranda', 'url' => route('home'), 'icon' => '🏡'],
        ['label' => 'PPID Desa', 'url' => route('public.ppid.index')],
        ['label' => 'Dokumen Publik (DIP)'],
    ],
    'eyebrow' => 'Daftar Informasi Publik (DIP Desa)',
    'eyebrowIcon' => '📁',
    'title' => 'Repositori Dokumen Publik',
    'description' => 'Akses dan unduh berkas resmi penyelenggaraan pemerintahan desa seperti RPJMDes, RKPDes, LPPD, LKPPD, dan Peraturan Desa sesuai amanat UU No. 14 Tahun 2008.',
])
@endcomponent

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <div class="bg-white p-6 rounded-3xl border border-emerald-100 shadow-sm">
        <form action="{{ route('public.ppid.documents') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="lg:col-span-2">
                <label for="keyword" class="block text-xs font-semibold text-slate-700 mb-1">Cari Dokumen</label>
                <input
                    type="text"
                    id="keyword"
                    name="keyword"
                    value="{{ $keyword }}"
                    placeholder="Judul atau kata kunci dokumen..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                >
            </div>

            <div>
                <label for="category" class="block text-xs font-semibold text-slate-700 mb-1">Kategori KIP</label>
                <select id="category" name="category" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white">
                    <option value="">Semua Kategori</option>
                    <option value="berkala" {{ $category === 'berkala' ? 'selected' : '' }}>Informasi Berkala</option>
                    <option value="setiap_saat" {{ $category === 'setiap_saat' ? 'selected' : '' }}>Informasi Setiap Saat</option>
                    <option value="serta_merta" {{ $category === 'serta_merta' ? 'selected' : '' }}>Informasi Serta Merta</option>
                </select>
            </div>

            <div>
                <label for="year" class="block text-xs font-semibold text-slate-700 mb-1">Tahun</label>
                <select id="year" name="year" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ (string)$year === (string)$y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-sm transition">
                    Filter
                </button>
                @if($keyword || $category || $documentType || $year)
                    <a href="{{ route('public.ppid.documents') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($documents as $doc)
            @include('themes.emerald.partials.document-card', ['doc' => $doc])
        @empty
            @include('themes.emerald.partials.empty-state', [
                'icon' => '📭',
                'title' => 'Tidak ada dokumen publik yang sesuai kriteria pencarian.',
                'description' => 'Coba atur ulang kata kunci atau filter tahun pencarian Anda.',
                'class' => 'col-span-full',
            ])
        @endforelse
    </div>

    <div class="pt-4">
        {{ $documents->links() }}
    </div>
</div>
@endsection
