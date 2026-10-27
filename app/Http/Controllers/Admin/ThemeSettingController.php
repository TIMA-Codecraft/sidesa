<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ThemeSettingController extends Controller
{
    /**
     * Tampilkan daftar tema yang tersedia dan tema aktif.
     */
    public function index(): View
    {
        $activeTheme = active_theme();

        // Scan folder resources/views/themes untuk mendeteksi tema yang terpasang
        $themesPath = resource_path('views/themes');
        $availableThemes = [];

        // Definisi metadata tema bawaan
        $themeMeta = [
            'default' => [
                'name' => 'Default (Klasik SiDesa)',
                'description' => 'Tema resmi bawaan SiDesa dengan palet biru langit cerah, navigasi lengkap, dan tata letak responsif.',
                'author' => 'SiDesa Core Team',
                'version' => '1.1.0',
                'screenshot' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80',
            ],
            'emerald' => [
                'name' => 'Emerald Nature',
                'description' => 'Tema bertema alam bernuansa hijau zamrud asri, cocok untuk desa agrowisata dan pedesaan hijau.',
                'author' => 'SiDesa Community',
                'version' => '1.1.0',
                'screenshot' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        if (File::isDirectory($themesPath)) {
            $directories = File::directories($themesPath);
            foreach ($directories as $dir) {
                $folder = basename($dir);
                $availableThemes[$folder] = $themeMeta[$folder] ?? [
                    'name' => ucfirst($folder),
                    'description' => "Tema kustom '{$folder}' dari pihak ketiga.",
                    'author' => 'Pihak Ketiga',
                    'version' => '1.0.0',
                    'screenshot' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=600&q=80',
                ];
            }
        }

        if (empty($availableThemes)) {
            $availableThemes['default'] = $themeMeta['default'];
        }

        return view('admin.themes.index', compact('availableThemes', 'activeTheme'));
    }

    /**
     * Ubah tema portal publik aktif.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme' => ['required', 'string', 'alpha_dash'],
        ]);

        $themeFolder = $validated['theme'];
        $themePath = resource_path("views/themes/{$themeFolder}");

        if (! File::isDirectory($themePath)) {
            return redirect()->route('admin.themes.index')
                ->with('error', "Folder tema '{$themeFolder}' tidak ditemukan di sistem.");
        }

        Setting::set('active_theme', $themeFolder, 'appearance', 'Tema aktif portal publik');

        return redirect()->route('admin.themes.index')
            ->with('success', "Tema portal publik berhasil diubah ke '{$themeFolder}'.");
    }
}
