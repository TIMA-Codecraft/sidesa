@extends(theme_layout())

@section('title', 'PPID Desa - Layanan Keterbukaan Informasi Publik')

@section('content')
@component('themes.default.partials.page-hero', [
    'eyebrow' => 'UU No. 14 Tahun 2008 & Perki No. 1 Tahun 2018',
    'eyebrowIcon' => '📢',
    'title' => 'PPID Desa ' . village_name(withPrefix: false),
    'description' => 'Pejabat Pengelola Informasi dan Dokumentasi (PPID) Desa hadir untuk menjamin hak masyarakat desa dalam memperoleh informasi publik secara cepat, tepat waktu, dan transparan.',
])
    <div class="mt-8 flex flex-wrap gap-3">
        <a href="{{ route('public.ppid.requests.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs shadow-lg transition">
            <span>📝</span>
            <span>Permohonan Informasi Daring</span>
        </a>
        <a href="{{ route('public.ppid.documents') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs backdrop-blur-sm border border-white/20 transition">
            <span>📁</span>
            <span>Daftar Dokumen Publik (DIP)</span>
        </a>
        <a href="{{ route('public.ppid.tracking') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white/5 hover:bg-white/15 text-sky-100 font-bold text-xs border border-white/20 transition">
            <span>🔍</span>
            <span>Cek Status Tiket</span>
        </a>
    </div>
@endcomponent

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
    <!-- Statistik Singkat -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        @include('themes.default.partials.stat-card', [
            'icon' => '📄',
            'tone' => 'sky',
            'value' => $stats['total_documents'],
            'label' => 'Dokumen Publik Terbit',
        ])
        @include('themes.default.partials.stat-card', [
            'icon' => '📬',
            'tone' => 'amber',
            'value' => $stats['total_requests'],
            'label' => 'Permohonan Informasi Diterima',
        ])
        @include('themes.default.partials.stat-card', [
            'icon' => '✅',
            'tone' => 'emerald',
            'value' => $stats['resolved_requests'],
            'label' => 'Permohonan Telah Ditanggapi',
        ])
    </div>

    <!-- Maklumat Pelayanan Informasi -->
    <div class="bg-gradient-to-br from-sky-50 to-indigo-50 rounded-2xl p-8 border border-sky-100 shadow-sm">
        <div class="max-w-3xl space-y-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-sky-100 text-sky-800 uppercase tracking-wider">
                ⚖️ Maklumat Pelayanan Informasi Publik Desa
            </span>
            <blockquote class="text-lg sm:text-xl font-bold text-slate-800 italic leading-snug">
                "{{ $ppidProfile['maklumat'] }}"
            </blockquote>
            <div class="pt-2 text-xs text-slate-600 flex flex-wrap items-center gap-2">
                <span>Ditetapkan oleh Pemerintah Desa {{ village_name(withPrefix: false) }}</span>
                <span>•</span>
                <span>Berdasarkan UU KIP No. 14 Tahun 2008</span>
            </div>
        </div>
    </div>

    <!-- Struktur Organisasi PPID Desa -->
    <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm space-y-8">
        <div>
            <h2 class="text-xl font-black text-slate-800">Struktur Organisasi PPID Desa</h2>
            <p class="text-xs text-slate-500 mt-1">Sesuai Peraturan Komisi Informasi (Perki) No. 1 Tahun 2018 tentang Standar Layanan Informasi Publik Desa.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Atasan PPID -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 uppercase tracking-wider">
                    Atasan PPID Desa
                </span>
                <h3 class="text-base font-bold text-slate-800">{{ $ppidProfile['kades_name'] }}</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Bertanggung jawab penuh atas pengelolaan keterbukaan informasi publik desa dan menetapkan keputusan atas pengajuan keberatan permohonan informasi.
                </p>
            </div>

            <!-- PPID Desa -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-100 text-sky-800 uppercase tracking-wider">
                    Pejabat Pengelola Informasi & Dokumentasi (PPID)
                </span>
                <h3 class="text-base font-bold text-slate-800">{{ $ppidProfile['sekdes_name'] }}</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Mengoordinasikan pengumpulan, pendokumentasian, verifikasi, serta penyampaian informasi publik desa kepada masyarakat pemohon.
                </p>
            </div>
        </div>
    </div>

    <!-- Dokumen Publik Terbaru -->
    <div class="space-y-6">
        @include('themes.default.partials.section-header', [
            'title' => 'Dokumen Publik Berkala Terbaru',
            'description' => 'Unduh berkas resmi perencanaan dan pertanggungjawaban desa.',
            'actionUrl' => route('public.ppid.documents'),
            'actionLabel' => 'Lihat Semua Dokumen',
        ])

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($latestDocuments as $doc)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition gap-4">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                {{ $doc->document_type }}
                            </span>
                            @if($doc->year)
                                <span class="text-[11px] font-semibold text-slate-400">Tahun {{ $doc->year }}</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-sm text-slate-800 line-clamp-2">{{ $doc->title }}</h3>
                        <p class="text-xs text-slate-500 line-clamp-2">{{ $doc->description ?? 'Tidak ada ringkasan keterangan.' }}</p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] font-mono text-slate-400">{{ $doc->formatted_file_size }}</span>
                        <a href="{{ route('public.ppid.documents.download', $doc) }}" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-sky-50 hover:bg-sky-100 text-sky-700 text-xs font-semibold transition gap-1">
                            <span>⬇️ Unduh</span>
                        </a>
                    </div>
                </div>
            @empty
                @include('themes.default.partials.empty-state', [
                    'icon' => '📄',
                    'title' => 'Belum ada dokumen publik',
                    'description' => 'Belum ada dokumen publik berkala yang diunggah.',
                    'class' => 'col-span-full',
                ])
            @endforelse
        </div>
    </div>
</div>
@endsection
