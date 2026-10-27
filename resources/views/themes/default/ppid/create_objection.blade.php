@extends(theme_layout())

@section('title', 'Formulir Pengajuan Keberatan Informasi Publik - PPID Desa')

@section('content')
@include('themes.default.partials.page-hero', [
    'variant' => 'amber',
    'eyebrow' => 'Mekanisme Keberatan KIP (UU 14/2008 & Perki 1/2018)',
    'eyebrowIcon' => '⚖️',
    'title' => 'Pengajuan Keberatan Informasi',
    'description' => 'Keberatan diajukan kepada Atasan PPID (Kepala Desa) atas permohonan informasi publik yang ditolak, tidak ditanggapi, atau tidak dipenuhi sebagaimana mestinya.',
    'container' => 'max-w-4xl',
])

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 sm:p-10 space-y-8">
        <!-- Rujukan Tiket Asal -->
        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-2">
            <span class="font-bold text-slate-700 block">Rujukan Permohonan Asal:</span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-slate-600">
                <div><b>Nomor Tiket:</b> <span class="font-mono">{{ $infoRequest->ticket_number }}</span></div>
                <div><b>Nama Pemohon:</b> {{ $infoRequest->applicant_name }}</div>
                <div class="sm:col-span-2"><b>Informasi yang Dimohon:</b> {{ $infoRequest->information_requested }}</div>
            </div>
        </div>

        @if ($errors->any())
            @include('themes.default.partials.alert', [
                'tone' => 'danger',
                'title' => 'Mohon periksa isian Anda:',
                'items' => $errors->all(),
            ])
        @endif

        <form action="{{ route('public.ppid.objections.store', $infoRequest->ticket_number) }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="reason_code" class="block text-xs font-semibold text-slate-700 mb-1">
                    Alasan Pengajuan Keberatan (Sesuai Perki No. 1/2018) <span class="text-rose-500">*</span>
                </label>
                <select 
                    name="reason_code" 
                    id="reason_code" 
                    required 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white"
                >
                    <option value="">Pilih Alasan Keberatan...</option>
                    <option value="rejected" {{ old('reason_code') === 'rejected' ? 'selected' : '' }}>1. Permohonan Informasi Ditolak</option>
                    <option value="not_provided" {{ old('reason_code') === 'not_provided' ? 'selected' : '' }}>2. Informasi Berkala Tidak Disediakan</option>
                    <option value="not_responded" {{ old('reason_code') === 'not_responded' ? 'selected' : '' }}>3. Permohonan Tidak Ditanggapi dalam Batas Waktu</option>
                    <option value="not_as_requested" {{ old('reason_code') === 'not_as_requested' ? 'selected' : '' }}>4. Permohonan Ditanggapi Tidak Sebagaimana Diminta</option>
                    <option value="excessive_fee" {{ old('reason_code') === 'excessive_fee' ? 'selected' : '' }}>5. Pengenaan Biaya yang Tidak Wajar</option>
                    <option value="late_delivery" {{ old('reason_code') === 'late_delivery' ? 'selected' : '' }}>6. Penyampaian Informasi Melebihi Batas Waktu Layanan</option>
                </select>
            </div>

            <div>
                <label for="objection_detail" class="block text-xs font-semibold text-slate-700 mb-1">
                    Kronologi & Rincian Alasan Keberatan <span class="text-rose-500">*</span>
                </label>
                <textarea 
                    name="objection_detail" 
                    id="objection_detail" 
                    rows="5" 
                    required 
                    placeholder="Uraikan secara jelas alasan dan argumentasi keberatan Anda kepada Kepala Desa (Atasan PPID)..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none"
                >{{ old('objection_detail') }}</textarea>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('public.ppid.tracking.show', $infoRequest->ticket_number) }}" class="text-xs text-slate-500 hover:text-slate-800">
                    &larr; Batal & Kembali
                </a>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-xl shadow-md transition text-xs flex items-center gap-2">
                    <span>⚖️</span>
                    <span>Kirim Berkas Keberatan ke Kepala Desa</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
