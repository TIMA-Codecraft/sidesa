@extends('admin.layouts.app')

@section('title', 'Pratinjau Template ' . $letterTemplate->code)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.letter-templates.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Pratinjau HTML: {{ $letterTemplate->name }}</h2>
                <p class="text-xs text-slate-500">Simulasi tampilan cetak surat resmi menggunakan data contoh kependudukan desa.</p>
            </div>
        </div>

        <div class="flex space-x-2">
            <a href="{{ route('admin.letter-templates.edit', $letterTemplate) }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 transition space-x-1">
                <span>✏️</span>
                <span>Edit Template</span>
            </a>
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 transition shadow space-x-1">
                <span>🖨️</span>
                <span>Cetak / Print</span>
            </button>
        </div>
    </div>

    <!-- Paper Container Simulation (A4 Look) -->
    <div class="bg-white p-10 sm:p-14 rounded-2xl border border-slate-200 shadow-md font-serif text-slate-900 leading-relaxed max-w-3xl mx-auto">
        <!-- KOP SURAT DESA -->
        <div class="pb-3 border-b-4 border-double border-slate-900 mb-6">
            @php
                $kopLogo = village_logo();
            @endphp
            <div class="flex items-center justify-between gap-4">
                @if($kopLogo)
                    <div class="w-20 flex-shrink-0 text-center">
                        <img src="{{ $kopLogo }}" alt="Logo Desa" class="w-16 h-16 object-contain mx-auto">
                    </div>
                @endif
                <div class="flex-1 text-center">
                    <h3 class="text-xs sm:text-sm font-bold tracking-wider uppercase m-0">PEMERINTAH KABUPATEN {{ strtoupper($regencyName) }}</h3>
                    <h3 class="text-xs sm:text-sm font-bold tracking-wider uppercase m-0">KECAMATAN {{ strtoupper($districtName) }}</h3>
                    <h2 class="text-base sm:text-xl font-extrabold tracking-widest uppercase m-0 mt-1">DESA {{ strtoupper(village_name(withPrefix: false)) }}</h2>
                    <div class="text-[11px] text-slate-600 italic mt-1 font-sans">
                        {{ $villageAddress }} | Kode Pos: {{ $postalCode }} | Telp: {{ $villagePhone }} | Email: {{ $villageEmail }}
                    </div>
                </div>
                @if($kopLogo)
                    <div class="w-20 flex-shrink-0"></div>
                @endif
            </div>
        </div>

        <!-- JUDUL SURAT -->
        <div class="text-center mb-6">
            <div class="text-sm sm:text-base font-bold uppercase underline tracking-wide">{{ $letterTemplate->name }}</div>
            <div class="text-xs font-sans text-slate-700 mt-1">Nomor: 470/001/{{ $letterTemplate->code }}/Ds/{{ date('Y') }}</div>
        </div>

        <!-- ISI SURAT HASIL RENDER TEMPLATE -->
        <div class="text-xs sm:text-sm text-justify leading-relaxed space-y-3 prose max-w-none">
            {!! $renderedContent !!}
        </div>

        <!-- TANDA TANGAN (TTE) KEPALA DESA -->
        <div class="mt-10 flex justify-end">
            <div class="w-64 text-center text-xs font-sans">
                <div>{{ $villageName }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                <div class="font-bold mt-1 mb-2">Kepala Desa {{ village_name(withPrefix: false) }}</div>
                
                <div class="my-3 p-2 bg-slate-50 border border-slate-200 rounded-xl inline-block">
                    <div class="w-20 h-20 bg-slate-200 text-slate-500 flex items-center justify-center text-[10px] font-mono mx-auto rounded">
                        [QR CODE TTE]
                    </div>
                    <div class="text-[9px] text-slate-500 mt-1 italic">
                        Ditandatangani secara elektronik (TTE)
                    </div>
                </div>

                <div class="font-bold underline text-xs">H. Mulyadi, S.Sos.</div>
                <div class="text-[11px] text-slate-600">NIP. 197508152005011002</div>
            </div>
        </div>
    </div>
</div>
@endsection
