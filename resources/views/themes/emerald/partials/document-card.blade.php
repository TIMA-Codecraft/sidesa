@php
    $compact = $compact ?? false;
    $description = $doc->description ?? 'Dokumen resmi publikasi PPID Desa.';
@endphp
<div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-emerald-200 transition space-y-4">
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                {{ $doc->document_type }}
            </span>
            @if ($doc->year)
                <span class="text-[11px] font-semibold text-slate-400">Tahun {{ $doc->year }}</span>
            @endif
        </div>
        <h3 class="font-bold text-sm text-slate-800 leading-snug line-clamp-2">{{ $doc->title }}</h3>
        <p class="text-xs text-slate-500 {{ $compact ? 'line-clamp-2' : 'line-clamp-3' }} leading-relaxed">{{ $description }}</p>
    </div>

    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
        <div class="text-[11px] text-slate-400">
            <span class="font-mono">{{ $doc->formatted_file_size }}</span>
            @unless ($compact)
                <span> • </span>
                <span>{{ $doc->download_count }}x diunduh</span>
            @endunless
        </div>
        <a
            href="{{ route('public.ppid.documents.download', $doc) }}"
            class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-semibold transition space-x-1 {{ $compact ? 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm' }}"
        >
            <span>⬇️ Unduh</span>
        </a>
    </div>
</div>
