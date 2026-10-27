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

        if (File::isDirectory($themesPath)) {
            foreach (File::directories($themesPath) as $dir) {
                $folder = basename($dir);
                $availableThemes[$folder] = $this->themeMetadata($folder, $dir);
            }
        }

        if (empty($availableThemes)) {
            $availableThemes['default'] = $this->themeMetadata('default', $themesPath.'/default');
        }

        return view('admin.themes.index', compact('availableThemes', 'activeTheme'));
    }

    /**
     * Baca metadata tema dari theme.json, lengkapi dengan nilai bawaan.
     *
     * @return array{name: string, description: string, author: string, version: string, screenshot: string}
     */
    private function themeMetadata(string $folder, string $path): array
    {
        $metadata = [
            'name' => ucfirst($folder),
            'description' => "Tema kustom '{$folder}' dari pihak ketiga.",
            'author' => 'Pihak Ketiga',
            'version' => '1.0.0',
            'screenshot' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=600&q=80',
        ];

        $manifestPath = $path.'/theme.json';

        if (! File::exists($manifestPath)) {
            return $metadata;
        }

        $manifest = json_decode((string) File::get($manifestPath), true);

        if (! is_array($manifest)) {
            return $metadata;
        }

        foreach (array_keys($metadata) as $key) {
            if (isset($manifest[$key]) && is_string($manifest[$key])) {
                $metadata[$key] = $manifest[$key];
            }
        }

        return $metadata;
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
