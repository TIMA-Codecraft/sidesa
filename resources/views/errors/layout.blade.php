<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Terjadi Kesalahan') - {{ \App\Models\Setting::get('village_name', 'Desa Sukamaju') }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">
    <!-- Header Minimalis Navigasi Error -->
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-bold text-lg shadow-sm group-hover:bg-emerald-700 transition">
                    {{ substr(\App\Models\Setting::get('village_name', 'S'), 0, 1) }}
                </div>
                <div>
                    <span class="block font-bold text-slate-900 leading-tight group-hover:text-emerald-700 transition">
                        {{ \App\Models\Setting::get('village_name', 'Desa Sukamaju') }}
                    </span>
                    <span class="block text-xs text-slate-500">
                        {{ \App\Models\Setting::get('subdistrict_name', 'Kecamatan') }}, {{ \App\Models\Setting::get('district_name', 'Kabupaten') }}
                    </span>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <a href="/" class="text-sm font-medium text-slate-600 hover:text-emerald-600 transition">
                    Beranda
                </a>
                <span class="text-slate-300">|</span>
                <a href="/citizen/letters/create" class="text-sm font-medium text-slate-600 hover:text-emerald-600 transition">
                    Layanan Surat
                </a>
                <span class="text-slate-300">|</span>
                <a href="/ppid" class="text-sm font-medium text-slate-600 hover:text-emerald-600 transition">
                    PPID Desa
                </a>
            </div>
        </div>
    </header>

    <!-- Konten Utama Error -->
    <main class="flex-1 flex items-center justify-center px-4 py-16">
        <div class="max-w-xl w-full text-center">
            @yield('content')
        </div>
    </main>

    <!-- Footer Error -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} Pemerintah Desa {{ village_name(withPrefix: false) }}. Sistem Informasi Desa (SiDesa).</p>
            <p class="mt-1 text-slate-400">Jika Anda membutuhkan bantuan, hubungi kantor desa melalui telepon {{ \App\Models\Setting::get('village_phone', '-') }} atau email {{ \App\Models\Setting::get('village_email', '-') }}.</p>
        </div>
    </footer>
</body>
</html>
