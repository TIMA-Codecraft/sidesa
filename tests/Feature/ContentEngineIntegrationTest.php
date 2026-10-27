<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Content;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Ttpryg\ContentEngine\Application\Service\CategoryService;
use Ttpryg\ContentEngine\Application\Service\ContentService;
use Ttpryg\ContentEngine\Contracts\SlugGeneratorInterface;
use Ttpryg\ContentEngine\Domain\Repository\CategoryRepositoryInterface;
use Ttpryg\ContentEngine\Domain\Repository\ContentRepositoryInterface;

class ContentEngineIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected User $superadmin;

    protected User $perangkat;

    protected User $warga;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superadmin = User::where('email', 'admin@sidesa.timacodecraft.com')->firstOrFail();
        $this->perangkat = User::where('email', 'perangkat@sidesa.timacodecraft.com')->firstOrFail();
        $this->warga = User::where('email', 'warga@sidesa.timacodecraft.com')->firstOrFail();
    }

    /**
     * Test ContentEngine IoC container service bindings.
     */
    public function test_content_engine_services_resolve_from_container(): void
    {
        $this->assertInstanceOf(ContentService::class, app(ContentService::class));
        $this->assertInstanceOf(CategoryService::class, app(CategoryService::class));
        $this->assertInstanceOf(ContentRepositoryInterface::class, app(ContentRepositoryInterface::class));
        $this->assertInstanceOf(CategoryRepositoryInterface::class, app(CategoryRepositoryInterface::class));
        $this->assertInstanceOf(SlugGeneratorInterface::class, app(SlugGeneratorInterface::class));
    }

    /**
     * Test public home page renders latest articles.
     */
    public function test_public_home_renders_latest_articles(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Kabar Desa Terkini');
        $response->assertSee('Penyaluran Bantuan Langsung Tunai (BLT) Dana Desa Triwulan Berjalan Sukses');
    }

    /**
     * Test public articles index lists published articles.
     */
    public function test_public_articles_index_page(): void
    {
        $response = $this->get('/berita');

        $response->assertStatus(200);
        $response->assertSee('Kabar & Informasi Terkini');
        $response->assertSee('Penyaluran Bantuan Langsung Tunai (BLT) Dana Desa Triwulan Berjalan Sukses');
        $response->assertSee('Pembangunan Jalan Usaha Tani Dusun Sukamaju Resmi Dimulai');
    }

    /**
     * Test public article detail page and view count increment.
     */
    public function test_public_article_detail_page_and_view_count_increment(): void
    {
        $article = Content::posts()->published()->where('slug', 'penyaluran-blt-dana-desa-sukses')->firstOrFail();
        $initialViews = $article->view_count;

        $response = $this->get('/berita/'.$article->slug);

        $response->assertStatus(200);
        $response->assertSee($article->title);
        $response->assertSee('kali dibaca');

        $this->assertEquals($initialViews + 1, $article->fresh()->view_count);
    }

    /**
     * Test public category page filters articles.
     */
    public function test_public_category_filter_page(): void
    {
        $response = $this->get('/kategori/pembangunan');

        $response->assertStatus(200);
        $response->assertSee('Pembangunan');
        $response->assertSee('Pembangunan Jalan Usaha Tani Dusun Sukamaju Resmi Dimulai');
    }

    /**
     * Test public static page shows content and increments views.
     */
    public function test_public_static_page_shows_content_and_increments_views(): void
    {
        $page = Content::pages()->published()->where('slug', 'profil-desa')->firstOrFail();
        $initialViews = $page->view_count;

        $response = $this->get('/halaman/'.$page->slug);

        $response->assertStatus(200);
        $response->assertSee($page->title);
        $response->assertSee('Halaman Profil Terkait');

        $this->assertEquals($initialViews + 1, $page->fresh()->view_count);
    }

    /**
     * Test draft articles return 404 on public route.
     */
    public function test_draft_articles_return_404_on_public_route(): void
    {
        $draft = Content::create([
            'tenant_type' => 'village',
            'tenant_id' => '1',
            'author_id' => $this->superadmin->id,
            'type' => Content::TYPE_POST,
            'title' => 'Draf Rahasia Desa',
            'slug' => 'draf-rahasia-desa',
            'summary' => 'Ringkasan draf',
            'body' => 'Konten rahasia draf',
            'status' => Content::STATUS_DRAFT,
        ]);

        $response = $this->get('/berita/'.$draft->slug);
        $response->assertStatus(404);
    }

    /**
     * Test admin can view articles index.
     */
    public function test_admin_can_view_articles_index(): void
    {
        $response = $this->actingAs($this->superadmin)->get('/admin/articles');

        $response->assertStatus(200);
        $response->assertSee('Kabar & Berita Desa');
        $response->assertSee('Tulis Berita Baru');
    }

    /**
     * Test admin can create a new article.
     */
    public function test_admin_can_create_new_article(): void
    {
        $category = Category::firstOrCreate(['name' => 'Kesehatan', 'slug' => 'kesehatan', 'type' => 'category']);

        $response = $this->actingAs($this->perangkat)->post('/admin/articles', [
            'title' => 'Posyandu Balita dan Lansia Dusun II',
            'summary' => 'Jadwal pelayanan posyandu terpadu bulan ini.',
            'body' => 'Pelayanan imunisasi balita dan cek tensi gratis lansia dilaksanakan hari Selasa.',
            'status' => 'published',
            'categories' => [$category->id],
            'published_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect('/admin/articles');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contents', [
            'type' => 'post',
            'title' => 'Posyandu Balita dan Lansia Dusun II',
            'slug' => 'posyandu-balita-dan-lansia-dusun-ii',
            'status' => 'published',
        ]);
    }

    /**
     * Test admin can update article.
     */
    public function test_admin_can_update_article(): void
    {
        $article = Content::posts()->firstOrFail();

        $response = $this->actingAs($this->superadmin)->put('/admin/articles/'.$article->id, [
            'title' => 'Judul Artikel Diperbarui',
            'slug' => 'judul-artikel-diperbarui',
            'summary' => 'Ringkasan revisi',
            'body' => 'Isi berita setelah diedit oleh perangkat.',
            'status' => 'published',
        ]);

        $response->assertRedirect('/admin/articles');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contents', [
            'id' => $article->id,
            'title' => 'Judul Artikel Diperbarui',
            'slug' => 'judul-artikel-diperbarui',
        ]);
    }

    /**
     * Test admin can delete article (soft delete).
     */
    public function test_admin_can_delete_article(): void
    {
        $article = Content::posts()->firstOrFail();

        $response = $this->actingAs($this->superadmin)->delete('/admin/articles/'.$article->id);

        $response->assertRedirect('/admin/articles');
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('contents', ['id' => $article->id]);
    }

    /**
     * Test admin can create category using CategoryService.
     */
    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->superadmin)->post('/admin/categories', [
            'name' => 'Pariwisata & Budaya',
            'type' => 'category',
        ]);

        $response->assertRedirect('/admin/categories');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Pariwisata & Budaya',
            'slug' => 'pariwisata-budaya',
            'type' => 'category',
        ]);
    }

    /**
     * Test admin can create, update, and delete static pages.
     */
    public function test_admin_can_manage_static_pages(): void
    {
        // 1. Create
        $response = $this->actingAs($this->superadmin)->post('/admin/pages', [
            'title' => 'Sejarah Pembentukan Desa',
            'slug' => 'sejarah-desa',
            'summary' => 'Napak tilas berdirinya desa sejak era kolonial.',
            'body' => 'Desa didirikan oleh para tetua adat pada tahun 1928...',
            'status' => 'published',
            'sort_order' => 5,
        ]);

        $response->assertRedirect('/admin/pages');
        $this->assertDatabaseHas('contents', [
            'type' => 'page',
            'slug' => 'sejarah-desa',
            'status' => 'published',
        ]);

        $page = Content::pages()->where('slug', 'sejarah-desa')->firstOrFail();

        // 2. Update
        $updateResponse = $this->actingAs($this->superadmin)->put('/admin/pages/'.$page->id, [
            'title' => 'Sejarah & Asal-usul Desa',
            'slug' => 'sejarah-dan-asal-usul-desa',
            'summary' => 'Revisi ringkasan sejarah.',
            'body' => 'Konten revisi sejarah desa.',
            'status' => 'published',
            'sort_order' => 6,
        ]);

        $updateResponse->assertRedirect('/admin/pages');
        $this->assertDatabaseHas('contents', [
            'id' => $page->id,
            'title' => 'Sejarah & Asal-usul Desa',
            'slug' => 'sejarah-dan-asal-usul-desa',
        ]);

        // 3. Delete
        $deleteResponse = $this->actingAs($this->superadmin)->delete('/admin/pages/'.$page->id);
        $deleteResponse->assertRedirect('/admin/pages');
        $this->assertSoftDeleted('contents', ['id' => $page->id]);
    }

    /**
     * Test theme management and switching active theme.
     */
    public function test_theme_management_and_switching(): void
    {
        // 1. View theme settings
        $response = $this->actingAs($this->superadmin)->get('/admin/themes');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Tema Portal Publik');
        $response->assertSee('Default (Klasik SiDesa)');
        $response->assertSee('Emerald Nature');

        // 2. Switch theme to emerald
        $switchResponse = $this->actingAs($this->superadmin)->post('/admin/themes', [
            'theme' => 'emerald',
        ]);

        $switchResponse->assertRedirect('/admin/themes');
        $switchResponse->assertSessionHas('success');

        $this->assertEquals('emerald', Setting::get('active_theme'));
        $this->assertEquals('emerald', active_theme());

        // 3. Verify public home page now renders with emerald theme
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Portal Resmi Desa Wisata');
    }

    /**
     * Test warga cannot access admin CMS routes.
     */
    public function test_citizen_cannot_access_admin_cms(): void
    {
        $response = $this->actingAs($this->warga)->get('/admin/articles');
        $response->assertStatus(403);

        $catResponse = $this->actingAs($this->warga)->get('/admin/categories');
        $catResponse->assertStatus(403);

        $pageResponse = $this->actingAs($this->warga)->get('/admin/pages');
        $pageResponse->assertStatus(403);

        $themeResponse = $this->actingAs($this->warga)->get('/admin/themes');
        $themeResponse->assertStatus(403);

        $galleryResponse = $this->actingAs($this->warga)->get('/admin/galleries');
        $galleryResponse->assertStatus(403);
    }

    /**
     * Test public announcements page.
     */
    public function test_public_announcements_page(): void
    {
        $response = $this->get('/pengumuman');

        $response->assertStatus(200);
        $response->assertSee('Kategori: Pengumuman');
        $response->assertSee('Jadwal Pelayanan Posyandu Balita dan Lansia Serentak Bulan Ini');
    }

    /**
     * Test public galleries page.
     */
    public function test_public_galleries_page(): void
    {
        $response = $this->get('/galeri');

        $response->assertStatus(200);
        $response->assertSee('Galeri Foto Desa');
        $response->assertSee('Dokumentasi Panen Raya Padi Organik Dusun Sukamaju');
        $response->assertSee('Buka Album');
    }

    /**
     * Test public gallery detail show page.
     */
    public function test_public_gallery_show_page(): void
    {
        $album = Content::galleries()->published()->firstOrFail();
        $initialViews = $album->view_count;

        $response = $this->get('/galeri/'.$album->slug);

        $response->assertStatus(200);
        $response->assertSee($album->title);
        $response->assertSee('Daftar Foto dalam Album');
        $this->assertEquals($initialViews + 1, $album->fresh()->view_count);
    }

    /**
     * Test admin can create gallery album, manage photos inside it, and delete album.
     */
    public function test_admin_can_manage_galleries(): void
    {
        // 1. Create album
        $response = $this->actingAs($this->superadmin)->post('/admin/galleries', [
            'title' => 'Peresmian Balai Warga RW 03',
            'image_url' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1200&q=80',
            'summary' => 'Foto peresmian balai pertemuan warga.',
        ]);

        $response->assertRedirect('/admin/galleries');
        $this->assertDatabaseHas('contents', [
            'type' => 'gallery',
            'title' => 'Peresmian Balai Warga RW 03',
        ]);

        $gallery = Content::galleries()->where('title', 'Peresmian Balai Warga RW 03')->firstOrFail();
        $this->assertEquals(1, $gallery->photos()->count());

        // 2. View album photo management page
        $showResponse = $this->actingAs($this->superadmin)->get('/admin/galleries/'.$gallery->id);
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Kelola Foto: Peresmian Balai Warga RW 03');

        // 3. Add additional photo to album
        $addPhotoResponse = $this->actingAs($this->superadmin)->post('/admin/galleries/'.$gallery->id.'/photos', [
            'image_url' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?auto=format&fit=crop&w=1200&q=80',
            'caption' => 'Suasana pemotongan pita peresmian',
        ]);
        $addPhotoResponse->assertRedirect('/admin/galleries/'.$gallery->id);
        $this->assertEquals(2, $gallery->fresh()->photos()->count());

        $newPhoto = $gallery->photos()->where('caption', 'Suasana pemotongan pita peresmian')->firstOrFail();

        // 4. Delete photo from album
        $delPhotoResponse = $this->actingAs($this->superadmin)->delete('/admin/galleries/'.$gallery->id.'/photos/'.$newPhoto->id);
        $delPhotoResponse->assertRedirect('/admin/galleries/'.$gallery->id);
        $this->assertDatabaseMissing('gallery_photos', ['id' => $newPhoto->id]);

        // 5. Delete album
        $deleteResponse = $this->actingAs($this->superadmin)->delete('/admin/galleries/'.$gallery->id);
        $deleteResponse->assertRedirect('/admin/galleries');
        $this->assertSoftDeleted('contents', ['id' => $gallery->id]);
    }

    /**
     * Test admin dapat mengunggah file foto sampul artikel dan membersihkannya saat update / delete.
     */
    public function test_admin_can_upload_cover_image_for_article(): void
    {
        Storage::fake('public');

        $category = Category::firstOrFail();
        $file = UploadedFile::fake()->image('berita_desa.jpg', 800, 600);

        // 1. Simpan artikel dengan file upload
        $response = $this->actingAs($this->superadmin)->post('/admin/articles', [
            'title' => 'Pelatihan Budidaya Ikan Nila Warga Dusun Karang',
            'category_id' => $category->id,
            'summary' => 'Ringkasan pelatihan perikanan air tawar.',
            'body' => '<p>Artikel lengkap kegiatan pelatihan budidaya ikan nila bersama dinas kelautan.</p>',
            'status' => 'published',
            'cover_image_file' => $file,
        ]);

        $response->assertRedirect('/admin/articles');
        $this->assertDatabaseHas('contents', [
            'type' => 'post',
            'title' => 'Pelatihan Budidaya Ikan Nila Warga Dusun Karang',
        ]);

        $article = Content::posts()->where('title', 'Pelatihan Budidaya Ikan Nila Warga Dusun Karang')->firstOrFail();
        $this->assertNotNull($article->cover_image);
        $this->assertStringStartsWith('/storage/articles/', $article->cover_image);

        $storedFilePath = str_replace('/storage/', '', $article->cover_image);
        Storage::disk('public')->assertExists($storedFilePath);

        // 2. Update artikel dengan file baru (file lama harus dihapus dari storage)
        $newFile = UploadedFile::fake()->image('berita_desa_baru.png', 800, 600);
        $updateResponse = $this->actingAs($this->superadmin)->put('/admin/articles/'.$article->id, [
            'title' => 'Pelatihan Budidaya Ikan Nila Warga Dusun Karang - Diperbarui',
            'slug' => $article->slug,
            'category_id' => $category->id,
            'summary' => 'Ringkasan yang diperbarui.',
            'body' => '<p>Konten diperbarui.</p>',
            'status' => 'published',
            'cover_image_file' => $newFile,
        ]);

        $updateResponse->assertRedirect('/admin/articles');
        Storage::disk('public')->assertMissing($storedFilePath);

        $article->refresh();
        $updatedStoredPath = str_replace('/storage/', '', $article->cover_image);
        Storage::disk('public')->assertExists($updatedStoredPath);

        // 3. Hapus artikel (file di storage harus dibersihkan)
        $deleteResponse = $this->actingAs($this->superadmin)->delete('/admin/articles/'.$article->id);
        $deleteResponse->assertRedirect('/admin/articles');
        Storage::disk('public')->assertMissing($updatedStoredPath);
    }

    /**
     * Test admin dapat mengunggah file foto untuk album galeri dan foto detailnya.
     */
    public function test_admin_can_upload_images_for_gallery_and_photos(): void
    {
        Storage::fake('public');

        $albumCover = UploadedFile::fake()->image('album_cover.jpg', 800, 600);

        // 1. Buat album dengan file upload
        $response = $this->actingAs($this->superadmin)->post('/admin/galleries', [
            'title' => 'Pameran UMKM Kerajinan Bambu',
            'image_file' => $albumCover,
            'summary' => 'Foto produk kerajinan bambu unggulan warga.',
        ]);

        $response->assertRedirect('/admin/galleries');
        $gallery = Content::galleries()->where('title', 'Pameran UMKM Kerajinan Bambu')->firstOrFail();
        $firstPhoto = $gallery->photos()->firstOrFail();

        $this->assertStringStartsWith('/storage/galleries/', $firstPhoto->image_url);
        $firstPhotoPath = str_replace('/storage/', '', $firstPhoto->image_url);
        Storage::disk('public')->assertExists($firstPhotoPath);

        // 2. Tambah foto kedua via upload file
        $secondPhotoFile = UploadedFile::fake()->image('foto_dua.jpg', 800, 600);
        $addPhotoResponse = $this->actingAs($this->superadmin)->post('/admin/galleries/'.$gallery->id.'/photos', [
            'image_file' => $secondPhotoFile,
            'caption' => 'Anyaman tas bambu motif tradisional',
        ]);

        $addPhotoResponse->assertRedirect('/admin/galleries/'.$gallery->id);
        $secondPhoto = $gallery->photos()->where('caption', 'Anyaman tas bambu motif tradisional')->firstOrFail();
        $this->assertStringStartsWith('/storage/galleries/', $secondPhoto->image_url);
        $secondPhotoPath = str_replace('/storage/', '', $secondPhoto->image_url);
        Storage::disk('public')->assertExists($secondPhotoPath);

        // 3. Hapus foto kedua secara individual
        $delPhotoResponse = $this->actingAs($this->superadmin)->delete('/admin/galleries/'.$gallery->id.'/photos/'.$secondPhoto->id);
        $delPhotoResponse->assertRedirect('/admin/galleries/'.$gallery->id);
        Storage::disk('public')->assertMissing($secondPhotoPath);

        // 4. Hapus album secara keseluruhan (file foto album juga harus terhapus)
        $deleteAlbumResponse = $this->actingAs($this->superadmin)->delete('/admin/galleries/'.$gallery->id);
        $deleteAlbumResponse->assertRedirect('/admin/galleries');
        Storage::disk('public')->assertMissing($firstPhotoPath);
    }

    /**
     * Test unique tenant slug constraint (uk_tenant_slug) on contents table.
     */
    public function test_unique_tenant_slug_constraint_prevents_duplicate_slug_for_same_tenant_and_type(): void
    {
        Content::create([
            'tenant_type' => 'village',
            'tenant_id' => '1',
            'type' => Content::TYPE_POST,
            'title' => 'Berita Pertama',
            'slug' => 'berita-sama',
            'status' => Content::STATUS_PUBLISHED,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Content::create([
            'tenant_type' => 'village',
            'tenant_id' => '1',
            'type' => Content::TYPE_POST,
            'title' => 'Berita Kedua',
            'slug' => 'berita-sama',
            'status' => Content::STATUS_PUBLISHED,
        ]);
    }
}
