@extends(theme_layout())

@section('title', $page->title . ' - ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju'))

@section('content')
@include('themes.default.partials.page-hero', [
    'eyebrow' => 'Informasi Resmi Desa',
    'eyebrowIcon' => '🏛️',
    'title' => $page->title,
    'description' => $page->summary,
    'breadcrumbs' => [
        ['label' => 'Beranda', 'url' => route('home')],
        ['label' => 'Profil Desa'],
        ['label' => $page->title],
    ],
])

<!-- Content & Sidebar Layout -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Main Content (3 cols) -->
        <main class="lg:col-span-3">
            <article class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
                @if($page->cover_image)
                    <div class="rounded-2xl overflow-hidden mb-6">
                        <img src="{{ $page->cover_image }}" alt="{{ $page->title }}" class="w-full max-h-96 object-cover">
                    </div>
                @endif

                <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed space-y-4 text-sm sm:text-base">
                    {!! $page->rendered_body !!}
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                    <span>Terakhir diperbarui: {{ $page->updated_at->translatedFormat('d F Y') }}</span>
                    <span>👁️ Dibaca: {{ number_format($page->view_count) }} kali</span>
                </div>
            </article>
        </main>

        <!-- Sidebar Navigation (1 col) -->
        <aside class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-800 text-sm flex items-center space-x-2">
                    <span>📑</span>
                    <span>Halaman Profil Terkait</span>
                </h3>

                <nav class="space-y-1">
                    @foreach($otherPages as $other)
                        <a href="{{ route('pages.show', $other->slug) }}" class="block px-3 py-2.5 rounded-xl text-xs font-medium transition {{ $other->id === $page->id ? 'bg-sky-50 text-sky-700 font-bold border border-sky-200' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            {{ $other->title }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <!-- Layanan Persuratan Widget -->
            <div class="bg-gradient-to-br from-sky-600 to-indigo-700 p-6 rounded-2xl text-white shadow-sm space-y-3">
                <div class="text-3xl">✉️</div>
                <h4 class="font-bold text-sm">Butuh Pengurusan Surat?</h4>
                <p class="text-xs text-sky-100 leading-relaxed">
                    Warga desa dapat mengajukan permohonan surat administrasi secara online mandiri dari rumah.
                </p>
                <a href="{{ route('citizen.letters.create') }}" class="inline-block mt-2 px-4 py-2 rounded-xl text-xs font-bold bg-white text-sky-800 hover:bg-sky-50 transition shadow-sm">
                    Ajukan Surat Mandiri &rarr;
                </a>
            </div>
        </aside>
    </div>
</div>
@endsection
