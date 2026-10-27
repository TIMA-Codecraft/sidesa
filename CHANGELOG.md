# Changelog

Semua perubahan penting pada SiDesa didokumentasikan di file ini.

Format mengikuti [Keep a Changelog](https://keepachangelog.com/id/1.1.0/) dan proyek ini menggunakan [Semantic Versioning](https://semver.org/lang/id/).

## [Unreleased]

### Added

- Kredit pengembang TimaCodeCraft beserta tautan `timacodecraft.com` pada footer tema publik.
- Sitemap XML dinamis di `/sitemap.xml` untuk beranda, berita, kategori, halaman statis, galeri, APBDes, peta, dan layanan PPID.
- `robots.txt` dinamis di `/robots.txt` yang mencantumkan URL sitemap dan memblokir area privat seperti admin, installer, layanan warga, serta verifikasi surat.
- Invalidasi cache sitemap otomatis saat konten, kategori, APBDes, atau dokumen PPID berubah.
- Feature test untuk sitemap, robots.txt, dan invalidasi cache.

### Changed

- Menyeragamkan komponen visual tema `default` (hero, breadcrumbs, kartu, badge, tombol, filter, dan empty state) melalui partial Blade bersama agar tampilan konsisten lintas halaman.
- Menyeragamkan komponen visual tema `emerald` (hero, breadcrumbs, section header, kartu artikel/dokumen/galeri/statistik, alert, dan empty state), menormalkan palet warna serta padding hero, dan mengganti URL hardcoded dengan named route.
- Versi tema `default` dan `emerald` dinaikkan ke `1.1.0`.
- Metadata tema (nama, deskripsi, penulis, versi, dan screenshot) kini dibaca dari `theme.json` di folder masing-masing tema.
- `public/robots.txt` statis diganti dengan endpoint dinamis agar URL sitemap mengikuti domain instalasi desa.
- Pembaruan namespace dan dependensi untuk AuditEngine 2.0 serta ContentEngine (#672, #674).
- Peningkatan responsivitas layout admin dengan pengelompokan menu sidebar model flyout (#676).

### Fixed

- Duplikasi kata "Desa" saat nama desa yang sudah berawalan "Desa" dirangkai dengan kata "Desa" (misalnya "Dokumentasi Desa Desa Sukamaju") pada halaman publik, footer, PDF, dan template surat.
- Duplikasi informasi akun pengguna pada navbar admin (#675).
- Integrasi `RbacManager` pada pengujian setelah perubahan namespace (#673).

## [1.0.0] - 2026-10-12

### Added

- Fondasi aplikasi Laravel, RBAC berbasis `auth-user`, dukungan multi-tema, dan pipeline CI/CD (#635, #636).
- Manajemen penduduk beserta tampilan daftar, tambah, ubah, dan detail (#640).
- Layanan surat mandiri warga dengan pengajuan, pelacakan, dan verifikasi surat (#641).
- CMS artikel berita dan halaman statis (#643).
- Manajemen galeri foto untuk admin dan portal publik (#644).
- Peta Web GIS desa dan transparansi APBDes (#645).
- Manajemen pengguna dan role (#646).
- Perlindungan data pribadi sesuai UU PDP No. 27/2022 (#647).
- Layanan PPID Desa sesuai UU No. 14/2008 dan Perki 1/2018, termasuk menu dan contoh Daftar Informasi Publik (#648).
- Halaman error kustom bertema untuk 404, 403, 419, 500, dan 503.
- Kesesuaian APBDes dengan Permendagri 20/2018 melalui lima bidang belanja dan restrukturisasi pembiayaan (#650).
- Kesesuaian aset Web GIS dengan Permendagri 1/2016 melalui klasifikasi KIB dan atribut kepemilikan hukum (#651).
- Tanda Tangan Elektronik tersertifikasi BSrE BSSN sesuai UU No. 1/2024 dan PP No. 71/2019 (#652).
- Modul pengaturan situs dan profil desa (#653).
- Unggah berkas langsung maupun melalui URL untuk artikel, galeri, dan fasilitas (#654).
- Unduh Salinan Kartu Keluarga dalam format PDF dengan jejak audit PDP (#655).
- Dukungan zona waktu Indonesia WIB, WITA, dan WIT secara dinamis (#656).
- Timeline mutasi dan riwayat peristiwa pada data keluarga dan penduduk (#657).
- Badge jumlah data pada sidebar admin (#658).
- Manajemen profil pengguna (#659).
- Wizard instalasi sistem multi-langkah (#660).
- Helper logo desa beserta integrasinya pada tampilan (#661).
- Dokumentasi VitePress dan otomatisasi deployment dokumentasi (#662, #663).
- Workflow deployment FTP beserta penyesuaian konfigurasinya (#664, #665).
- Konfigurasi mode demo (#667).

### Changed

- Restrukturisasi layout tema dan dukungan halaman error multi-tema (#649).
- Peningkatan dependensi `guzzlehttp/guzzle` ke 8.2 (#637).
- Pembaruan README dan lisensi proyek (#635).
- Penyesuaian konfigurasi deployment: pengecualian direktori vendor, penambahan `composer.phar`, peningkatan timeout unggah, dan pembaruan FTP Deploy Action (#669).

### Fixed

- Escaping nilai environment pada proses `updateEnvironment` (#666).
