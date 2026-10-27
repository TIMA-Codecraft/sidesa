<?php

declare(strict_types=1);

use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;

if (! function_exists('active_theme')) {
    /**
     * Mengambil nama tema publik yang saat ini aktif.
     */
    function active_theme(): string
    {
        try {
            if (Schema::hasTable('settings')) {
                return (string) Setting::get('active_theme', 'default');
            }
        } catch (\Throwable $e) {
            // Fallback saat database belum di-migrate
        }

        return 'default';
    }
}

if (! function_exists('theme_asset')) {
    /**
     * Menghasilkan URL aset statis untuk tema publik.
     */
    function theme_asset(string $path, ?string $theme = null): string
    {
        $selectedTheme = $theme ?: active_theme();
        $cleanPath = ltrim($path, '/');

        return asset("themes/{$selectedTheme}/{$cleanPath}");
    }
}

if (! function_exists('admin_asset')) {
    /**
     * Menghasilkan URL aset statis untuk dashboard admin.
     */
    function admin_asset(string $path): string
    {
        $cleanPath = ltrim($path, '/');

        return asset("assets_admin/{$cleanPath}");
    }
}

if (! function_exists('theme_layout')) {
    /**
     * Mengambil path layout master utama untuk tema publik yang aktif.
     */
    function theme_layout(): string
    {
        $theme = active_theme();
        if (view()->exists("themes.{$theme}.layouts.app")) {
            return "themes.{$theme}.layouts.app";
        }

        return 'themes.default.layouts.app';
    }
}

if (! function_exists('theme_view')) {
    /**
     * Memuat view Blade dari tema publik yang aktif.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $mergeData
     */
    function theme_view(string $view, array $data = [], array $mergeData = []): View
    {
        $theme = active_theme();
        $viewPath = "themes.{$theme}.{$view}";

        if (! view()->exists($viewPath)) {
            // Fallback ke tema default jika tema aktif tidak memiliki view tersebut
            $fallbackPath = "themes.default.{$view}";
            if (view()->exists($fallbackPath)) {
                return view($fallbackPath, $data, $mergeData);
            }
        }

        return view($viewPath, $data, $mergeData);
    }
}

if (! function_exists('public_header_menus')) {
    /**
     * Mengambil struktur menu navigasi header publik yang aktif.
     *
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Menu>
     */
    function public_header_menus(): \Illuminate\Database\Eloquent\Collection
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('menus')) {
                return \App\Models\Menu::header()
                    ->root()
                    ->active()
                    ->with(['children' => fn ($q) => $q->active()->orderBy('sort_order'), 'page'])
                    ->orderBy('sort_order')
                    ->get();
            }
        } catch (\Throwable $e) {
            // Fallback saat database belum di-migrate
        }

        return new \Illuminate\Database\Eloquent\Collection;
    }
}

if (! function_exists('app_timezone')) {
    /**
     * Mengambil identifier zona waktu aktif aplikasi.
     */
    function app_timezone(): string
    {
        return (string) config('app.timezone', 'Asia/Jakarta');
    }
}

if (! function_exists('timezone_label')) {
    /**
     * Mengambil singkatan resmi zona waktu Indonesia (WIB, WITA, atau WIT).
     */
    function timezone_label(?string $timezone = null): string
    {
        $tz = $timezone ?: app_timezone();

        return match ($tz) {
            'Asia/Makassar', 'Asia/Ujung_Pandang' => 'WITA',
            'Asia/Jayapura' => 'WIT',
            default => 'WIB',
        };
    }
}

if (! function_exists('indonesian_timezones')) {
    /**
     * Daftar pilihan zona waktu resmi di Indonesia beserta deskripsinya.
     *
     * @return array<string, string>
     */
    function indonesian_timezones(): array
    {
        return [
            'Asia/Jakarta' => 'WIB - Waktu Indonesia Barat (UTC+7 / Jakarta, Sumatera, Jawa, Kalbar, Kalteng)',
            'Asia/Makassar' => 'WITA - Waktu Indonesia Tengah (UTC+8 / Bali, NTB, NTT, Kalsel, Kaltim, Kaltara, Sulawesi)',
            'Asia/Jayapura' => 'WIT - Waktu Indonesia Timur (UTC+9 / Maluku, Papua)',
        ];
    }
}

if (! function_exists('app_name')) {
    /**
     * Mengambil nama resmi aplikasi (misal: "SiDesa").
     */
    function app_name(): string
    {
        return (string) config('app.name', 'SiDesa');
    }
}

if (! function_exists('app_version')) {
    /**
     * Mengambil nomor versi rilis aplikasi SiDesa.
     *
     * @param  bool  $withPrefix  Jika true, menyertakan awalan 'v' (misal: "v1.0.0").
     * @param  bool  $withName  Jika true, menyertakan nama aplikasi (misal: "SiDesa v1.0.0").
     */
    function app_version(bool $withPrefix = false, bool $withName = false): string
    {
        $version = (string) config('app.version', '1.0.0');
        $formatted = $withPrefix ? 'v'.$version : $version;

        if ($withName) {
            $name = app_name();

            return "{$name} ".($withPrefix ? $formatted : 'v'.$version);
        }

        return $formatted;
    }
}

if (! function_exists('app_name_version')) {
    /**
     * Menghasilkan nama aplikasi beserta nomor versinya (misal: "SiDesa v1.0.0").
     *
     * @param  string|null  $customName  Nama kustom jika ingin menimpa nama default aplikasi.
     */
    function app_name_version(?string $customName = null): string
    {
        $name = $customName ?: app_name();
        $version = app_version(withPrefix: true);

        return "{$name} {$version}";
    }
}

if (! function_exists('village_name')) {
    /**
     * Nama desa dari pengaturan; awalan "Desa" dapat dihilangkan agar tidak berulang.
     */
    function village_name(bool $withPrefix = true, string $fallback = 'Sukamaju'): string
    {
        $name = trim((string) \App\Models\Setting::get('village_name', $fallback));

        return $withPrefix ? $name : trim((string) preg_replace('/^desa\s+/i', '', $name));
    }
}

if (! function_exists('village_logo')) {
    /**
     * Mengambil URL logo resmi desa atau null jika belum diunggah.
     */
    function village_logo(): ?string
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $logo = \App\Models\Setting::get('village_logo');
                if (! empty($logo)) {
                    $logoStr = (string) $logo;

                    return str_starts_with($logoStr, 'http') ? $logoStr : asset($logoStr);
                }
            }
        } catch (\Throwable $e) {
            // Fallback jika database belum siap
        }

        return null;
    }
}

if (! function_exists('village_logo_base64')) {
    /**
     * Mengambil konten logo resmi desa dalam format Data URI Base64 (untuk DomPDF / cetak offline).
     */
    function village_logo_base64(): ?string
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $logo = \App\Models\Setting::get('village_logo');
                if (! empty($logo)) {
                    $clean = str_replace('/storage/', '', (string) $logo);
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($clean)) {
                        $path = \Illuminate\Support\Facades\Storage::disk('public')->path($clean);
                        $mime = mime_content_type($path) ?: 'image/png';

                        return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
                    }
                    if (file_exists(public_path(ltrim((string) $logo, '/')))) {
                        $path = public_path(ltrim((string) $logo, '/'));
                        $mime = mime_content_type($path) ?: 'image/png';

                        return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
                    }
                }
            }
        } catch (\Throwable $e) {
            // Fallback jika database belum siap
        }

        return null;
    }
}
