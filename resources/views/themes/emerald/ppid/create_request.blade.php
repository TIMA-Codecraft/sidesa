@extends(theme_layout())

@section('title', 'Formulir Permohonan Informasi Publik - PPID Desa ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju'))

@section('content')
@component('themes.emerald.partials.page-hero', [
    'breadcrumbs' => [
        ['label' => 'Beranda', 'url' => route('home'), 'icon' => '🏡'],
        ['label' => 'PPID Desa', 'url' => route('public.ppid.index')],
        ['label' => 'Permohonan Informasi'],
    ],
    'eyebrow' => 'Layanan Daring PPID Desa',
    'eyebrowIcon' => '📝',
    'title' => 'Permohonan Informasi Publik',
    'description' => 'Masyarakat dapat mengajukan permohonan informasi publik secara daring. Permohonan Anda akan ditindaklanjuti oleh Pejabat Pengelola Informasi dan Dokumentasi (PPID) Desa dalam batas waktu maksimal 10 hari kerja.',
])
@endcomponent

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-emerald-100 shadow-sm p-8 sm:p-10 space-y-8">
        <!-- Standar Waktu Informasi -->
        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-start space-x-3 text-xs text-emerald-950">
            <span class="text-xl shrink-0">ℹ️</span>
            <div class="space-y-1">
                <span class="font-bold block text-emerald-900">Ketentuan Layanan Informasi (UU No. 14 Tahun 2008 & Perki No. 1 Tahun 2018):</span>
                <p class="text-emerald-800/90 leading-relaxed">
                    1. Pemohon wajib mencantumkan identitas diri yang benar dan tujuan peruntukan informasi.<br>
                    2. PPID Desa wajib menyampaikan pemberitahuan tertulis atas permohonan paling lambat <b>10 (sepuluh) hari kerja</b> sejak permohonan diterima.
                </p>
            </div>
        </div>

        @if ($errors->any())
            @include('themes.emerald.partials.alert', [
                'tone' => 'danger',
                'title' => 'Terdapat data yang belum lengkap:',
                'items' => $errors->all(),
            ])
        @endif

        <form action="{{ route('public.ppid.requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Identitas Pemohon -->
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 inline-flex items-center justify-center text-xs font-bold">1</span>
                    <span>Identitas Pemohon Informasi</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="applicant_name" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Lengkap (Sesuai KTP) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="applicant_name" 
                            id="applicant_name" 
                            value="{{ old('applicant_name', auth()->user()?->name) }}" 
                            required 
                            placeholder="Nama pemohon..."
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="applicant_nik" class="block text-xs font-semibold text-slate-700 mb-1">
                            NIK (16 Digit - Opsional)
                        </label>
                        <input 
                            type="text" 
                            name="applicant_nik" 
                            id="applicant_nik" 
                            maxlength="16"
                            value="{{ old('applicant_nik') }}" 
                            placeholder="320xxxxxxxxxxxxx"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-mono text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="applicant_phone" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nomor Telepon / WhatsApp <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="applicant_phone" 
                            id="applicant_phone" 
                            value="{{ old('applicant_phone') }}" 
                            required 
                            placeholder="081234567890"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="applicant_email" class="block text-xs font-semibold text-slate-700 mb-1">
                            Alamat Email Aktif <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="applicant_email" 
                            id="applicant_email" 
                            value="{{ old('applicant_email', auth()->user()?->email) }}" 
                            required 
                            placeholder="nama@email.com"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                        >
                    </div>
                </div>

                <div>
                    <label for="applicant_address" class="block text-xs font-semibold text-slate-700 mb-1">
                        Alamat Lengkap Domisili <span class="text-rose-500">*</span>
                    </label>
                    <textarea 
                        name="applicant_address" 
                        id="applicant_address" 
                        rows="2" 
                        required 
                        placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan/desa..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    >{{ old('applicant_address') }}</textarea>
                </div>

                <div>
                    <label for="identity_card" class="block text-xs font-semibold text-slate-700 mb-1">
                        Unggah Foto / Scan KTP (Opsional, PDF/JPG/PNG max 2MB)
                    </label>
                    <input 
                        type="file" 
                        name="identity_card" 
                        id="identity_card" 
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-800 hover:file:bg-emerald-100 transition"
                    >
                </div>
            </div>

            <!-- Rincian Informasi -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 inline-flex items-center justify-center text-xs font-bold">2</span>
                    <span>Rincian Informasi yang Dibutuhkan</span>
                </h3>

                <div>
                    <label for="information_requested" class="block text-xs font-semibold text-slate-700 mb-1">
                        Rincian Informasi Publik yang Dimohonkan <span class="text-rose-500">*</span>
                    </label>
                    <textarea 
                        name="information_requested" 
                        id="information_requested" 
                        rows="4" 
                        required 
                        placeholder="Jelaskan secara spesifik informasi atau dokumen yang ingin Anda peroleh dari Pemerintah Desa..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    >{{ old('information_requested') }}</textarea>
                </div>

                <div>
                    <label for="purpose" class="block text-xs font-semibold text-slate-700 mb-1">
                        Tujuan Penggunaan Informasi Publik <span class="text-rose-500">*</span>
                    </label>
                    <textarea 
                        name="purpose" 
                        id="purpose" 
                        rows="3" 
                        required 
                        placeholder="Jelaskan peruntukan informasi (misal: penelitian akademis, monitoring pembangunan, dll)..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    >{{ old('purpose') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">
                        Cara Memperoleh Salinan Informasi <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="flex items-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-emerald-50/50 hover:border-emerald-300 transition">
                            <input type="radio" name="acquisition_way" value="online" {{ old('acquisition_way', 'online') === 'online' ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2.5 text-xs text-slate-700 font-medium">Elektronik / Unduhan</span>
                        </label>
                        <label class="flex items-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-emerald-50/50 hover:border-emerald-300 transition">
                            <input type="radio" name="acquisition_way" value="direct_view" {{ old('acquisition_way') === 'direct_view' ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2.5 text-xs text-slate-700 font-medium">Melihat Langsung</span>
                        </label>
                        <label class="flex items-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-emerald-50/50 hover:border-emerald-300 transition">
                            <input type="radio" name="acquisition_way" value="hardcopy" {{ old('acquisition_way') === 'hardcopy' ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2.5 text-xs text-slate-700 font-medium">Salinan Fisik / Cetak</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('public.ppid.index') }}" class="text-xs text-slate-500 hover:text-emerald-800 transition">
                    &larr; Kembali ke Beranda PPID
                </a>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl shadow-md shadow-emerald-600/20 transition text-xs flex items-center space-x-2">
                    <span>📨</span>
                    <span>Kirim Permohonan Informasi</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
