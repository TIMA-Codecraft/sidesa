@extends(theme_layout())

@section('title', 'Lacak Status Tiket Layanan Informasi - PPID Desa')

@section('content')
@include('themes.default.partials.page-hero', [
    'eyebrow' => 'Pelacakan Tiket Layanan Informasi',
    'eyebrowIcon' => '🔍',
    'title' => 'Lacak Status Tiket KIP',
    'description' => 'Pantau status progres tindak lanjut permohonan informasi publik atau pengajuan keberatan Anda secara real-time.',
    'align' => 'center',
    'container' => 'max-w-3xl',
])

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 sm:p-10 space-y-6">
        @if (session('error'))
            @include('themes.default.partials.alert', [
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
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 font-mono text-sm uppercase focus:ring-2 focus:ring-sky-500 focus:outline-none"
                >
                <p class="text-[11px] text-slate-400 mt-1">
                    Nomor tiket diberikan saat Anda selesai mengirimkan formulir permohonan informasi daring.
                </p>
            </div>

            <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition text-xs flex items-center justify-center gap-2">
                <span>🔎</span>
                <span>Periksa Status Tiket</span>
            </button>
        </form>

        <div class="pt-6 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-500 mb-2">Belum pernah mengajukan permohonan informasi?</p>
            <a href="{{ route('public.ppid.requests.create') }}" class="inline-flex items-center text-xs font-bold text-sky-600 hover:text-sky-800 gap-1">
                <span>Ajukan Permohonan Informasi Daring Sekarang &rarr;</span>
            </a>
        </div>
    </div>
</div>
@endsection
