@extends(theme_layout())

@section('title', 'Lacak Status Tiket Layanan Informasi - PPID Desa ' . village_name(withPrefix: false))

@section('content')
@component('themes.emerald.partials.page-hero', [
    'breadcrumbs' => [
        ['label' => 'Beranda', 'url' => route('home'), 'icon' => '🏡'],
        ['label' => 'PPID Desa', 'url' => route('public.ppid.index')],
        ['label' => 'Lacak Status Tiket'],
    ],
    'eyebrow' => 'Pelacakan Tiket Layanan Informasi',
    'eyebrowIcon' => '🔍',
    'title' => 'Lacak Status Tiket KIP',
    'description' => 'Pantau status progres tindak lanjut permohonan informasi publik atau pengajuan keberatan Anda secara real-time.',
])
@endcomponent

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl border border-emerald-100 shadow-sm p-8 sm:p-10 space-y-6">
        @if (session('error'))
            @include('themes.emerald.partials.alert', [
                'tone' => 'danger',
                'message' => session('error'),
            ])
        @endif

        <form action="{{ route('public.ppid.tracking.check') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="ticket_number" class="block text-xs font-semibold text-slate-700 mb-1">
                    Nomor Tiket Layanan (INF-... atau KBR-...)
                </label>
                <input 
                    type="text" 
                    name="ticket_number" 
                    id="ticket_number" 
                    required 
                    placeholder="Contoh: INF-20261005-0001 atau KBR-20261005-0001"
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 font-mono text-sm uppercase focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                >
                <p class="text-[11px] text-slate-400 mt-1">
                    Nomor tiket diberikan saat Anda selesai mengirimkan formulir permohonan informasi daring.
                </p>
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl shadow-md shadow-emerald-600/20 transition text-xs flex items-center justify-center space-x-2">
                <span>🔎</span>
                <span>Periksa Status Tiket</span>
            </button>
        </form>

        <div class="pt-6 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-500 mb-2">Belum pernah mengajukan permohonan informasi?</p>
            <a href="{{ route('public.ppid.requests.create') }}" class="inline-flex items-center text-xs font-bold text-emerald-700 hover:text-emerald-900 space-x-1">
                <span>Ajukan Permohonan Informasi Daring Sekarang &rarr;</span>
            </a>
        </div>
    </div>
</div>
@endsection
