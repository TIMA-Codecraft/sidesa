@extends(theme_layout())

@section('title', 'PPID Desa - Layanan Keterbukaan Informasi Publik')

@section('content')
@component('themes.emerald.partials.page-hero', [
    'breadcrumbs' => [
        ['label' => 'Beranda', 'url' => route('home'), 'icon' => '🏡'],
        ['label' => 'PPID Desa (Keterbukaan Informasi Publik)'],
    ],
    'eyebrow' => 'UU KIP No. 14 Tahun 2008 & Perki No. 1 Tahun 2018',
    'eyebrowIcon' => '📢',
    'title' => 'PPID Desa ' . $ppidProfile['village_name'],
    'description' => 'Pejabat Pengelola Informasi dan Dokumentasi (PPID) Desa menjamin hak masyarakat untuk memperoleh informasi publik secara cepat, tepat waktu, mudah, dan transparan.',
])
    <div class="pt-4 flex flex-wrap gap-3">
        <a href="{{ route('public.ppid.requests.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 text-slate-950 font-bold text-xs hover:opacity-90 transition shadow-lg shadow-emerald-950/40">
            <span>📝</span>
            <span>Permohonan Informasi Daring</span>
        </a>
        <a href="{{ route('public.ppid.documents') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/20 backdrop-blur-md transition">
            <span>📁</span>
            <span>Daftar Dokumen Publik (DIP)</span>
        </a>
        <a href="{{ route('public.ppid.tracking') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/20 backdrop-blur-md transition">
            <span>🔍</span>
            <span>Cek Status Tiket</span>
        </a>
    </div>
@endcomponent

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        @include('themes.emerald.partials.stat-card', [
            'tone' => 'emerald',
            'icon' => '📄',
            'value' => $stats['total_documents'],
            'label' => 'Dokumen Publik Terbit',
        ])
        @include('themes.emerald.partials.stat-card', [
            'tone' => 'amber',
            'icon' => '📬',
            'value' => $stats['total_requests'],
            'label' => 'Permohonan Informasi Diterima',
        ])
        @include('themes.emerald.partials.stat-card', [
            'tone' => 'teal',
            'icon' => '✅',
            'value' => $stats['resolved_requests'],
            'label' => 'Permohonan Telah Ditanggapi',
        ])
    </div>

    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-3xl p-8 border border-emerald-100 shadow-sm relative overflow-hidden">
        <div class="max-w-3xl space-y-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                ⚖️ Maklumat Pelayanan Informasi Publik Desa
            </span>
            <blockquote class="text-lg sm:text-xl font-bold text-slate-800 italic leading-snug">
                "{{ $ppidProfile['maklumat'] }}"
            </blockquote>
            <div class="pt-2 text-xs text-slate-600 flex items-center space-x-2">
                <span>Ditetapkan oleh Pemerintah Desa {{ $ppidProfile['village_name'] }}</span>
                <span>•</span>
                <span>Berdasarkan UU KIP No. 14 Tahun 2008</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-8">
        <div>
            <h2 class="text-xl font-black text-slate-800">Struktur Organisasi PPID Desa</h2>
            <p class="text-xs text-slate-500 mt-1">Sesuai Peraturan Komisi Informasi (Perki) No. 1 Tahun 2018 tentang Standar Layanan Informasi Publik Desa.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                    Atasan PPID Desa
                </span>
                <h3 class="text-base font-bold text-slate-800">{{ $ppidProfile['kades_name'] }}</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Bertanggung jawab penuh atas pengelolaan keterbukaan informasi publik desa dan menetapkan keputusan atas pengajuan keberatan permohonan informasi.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800 uppercase tracking-wider">
                    Pejabat Pengelola Informasi & Dokumentasi (PPID)
                </span>
                <h3 class="text-base font-bold text-slate-800">{{ $ppidProfile['sekdes_name'] }}</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Mengoordinasikan pengumpulan, pendokumentasian, verifikasi, serta penyampaian informasi publik desa kepada masyarakat pemohon.
                </p>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        @include('themes.emerald.partials.section-header', [
            'eyebrow' => 'Publikasi Resmi',
            'title' => 'Dokumen Publik Berkala Terbaru',
            'description' => 'Unduh berkas resmi perencanaan dan pertanggungjawaban desa.',
            'actionUrl' => route('public.ppid.documents'),
            'actionLabel' => 'Lihat Semua Dokumen',
        ])

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($latestDocuments as $doc)
                @include('themes.emerald.partials.document-card', ['doc' => $doc, 'compact' => true])
            @empty
                @include('themes.emerald.partials.empty-state', [
                    'title' => 'Belum ada dokumen publik berkala yang diunggah.',
                    'compact' => true,
                    'class' => 'col-span-full',
                ])
            @endforelse
        </div>
    </div>
</div>
@endsection
