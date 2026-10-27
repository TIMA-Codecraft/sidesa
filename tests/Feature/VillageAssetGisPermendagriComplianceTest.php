<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use App\Models\VillageFacility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VillageAssetGisPermendagriComplianceTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected User $superadmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superadmin = User::where('email', 'admin@sidesa.timacodecraft.com')->firstOrFail();
    }

    /**
     * Test model VillageFacility memiliki konstanta, accessor, dan scope Permendagri No. 1/2016.
     */
    public function test_village_facility_model_has_permendagri_kib_and_ownership_constants_and_accessors(): void
    {
        // 1. Cek KIB Constants & Metas (KIB A s.d. F)
        $kibMetas = VillageFacility::getKibMetas();
        $this->assertCount(6, $kibMetas);
        $this->assertArrayHasKey(VillageFacility::KIB_A, $kibMetas);
        $this->assertArrayHasKey(VillageFacility::KIB_B, $kibMetas);
        $this->assertArrayHasKey(VillageFacility::KIB_C, $kibMetas);
        $this->assertArrayHasKey(VillageFacility::KIB_D, $kibMetas);
        $this->assertArrayHasKey(VillageFacility::KIB_E, $kibMetas);
        $this->assertArrayHasKey(VillageFacility::KIB_F, $kibMetas);

        $this->assertEquals('KIB A', $kibMetas[VillageFacility::KIB_A]['code']);
        $this->assertEquals('Tanah', $kibMetas[VillageFacility::KIB_A]['name']);
        $this->assertEquals('Gedung dan Bangunan', $kibMetas[VillageFacility::KIB_C]['name']);

        // 2. Cek Status Hak Kepemilikan (TKD, APBDes, Hibah, dll.)
        $ownerships = VillageFacility::getOwnershipStatuses();
        $this->assertArrayHasKey(VillageFacility::OWNERSHIP_TKD, $ownerships);
        $this->assertArrayHasKey(VillageFacility::OWNERSHIP_APBDES, $ownerships);
        $this->assertArrayHasKey(VillageFacility::OWNERSHIP_HIBAH, $ownerships);

        // 3. Cek Accessors & Scopes pada record yang di-seed
        $facility = VillageFacility::where('register_code', 'KIB-C.001.1985')->firstOrFail();
        $this->assertTrue($facility->is_village_asset);
        $this->assertNotNull($facility->kib_meta);
        $this->assertEquals('KIB C', $facility->kib_meta['code']);
        $this->assertNotNull($facility->ownership_meta);
        $this->assertEquals('Tanah Kas Desa (TKD) / Kekayaan Asli Desa', $facility->ownership_meta['label']);
        $this->assertEquals('Rp 450.000.000', $facility->formatted_asset_value);

        // 4. Test Scopes
        $villageAssetsCount = VillageFacility::villageAssets()->count();
        $this->assertGreaterThan(0, $villageAssetsCount);

        $kibCCount = VillageFacility::kib(VillageFacility::KIB_C)->count();
        $this->assertGreaterThan(0, $kibCCount);

        $tkdCount = VillageFacility::ownership(VillageFacility::OWNERSHIP_TKD)->count();
        $this->assertGreaterThan(0, $tkdCount);
    }

    /**
     * Test admin dapat melihat daftar fasilitas dengan filter aset desa & KIB.
     */
    public function test_admin_can_view_facilities_index_with_asset_filters(): void
    {
        $response = $this->actingAs($this->superadmin)->get('/admin/facilities');
        $response->assertStatus(200);
        $response->assertSee('Fasilitas Umum & Infrastruktur Desa', false);
        $response->assertSee('Permendagri 1/2016');
        $response->assertSee('KIB-C.001.1985');

        // Filter Hanya Aset Desa
        $filterAssetResponse = $this->actingAs($this->superadmin)->get('/admin/facilities?is_village_asset=1');
        $filterAssetResponse->assertStatus(200);
        $filterAssetResponse->assertSee('Aset Desa');

        // Filter KIB C
        $filterKibResponse = $this->actingAs($this->superadmin)->get('/admin/facilities?kib_type=kib_c');
        $filterKibResponse->assertStatus(200);
        $filterKibResponse->assertSee('Gedung dan Bangunan');
    }

    /**
     * Test admin dapat menambah fasilitas baru dengan atribut yuridis aset desa dan tercatat di audit log.
     */
    public function test_admin_can_create_facility_with_permendagri_asset_attributes(): void
    {
        $payload = [
            'name' => 'Gedung Serbaguna Desa Sukamaju',
            'category' => 'pemerintahan',
            'latitude' => -6.918000,
            'longitude' => 107.610500,
            'address' => 'Jl. Pemuda No. 05 RW 03',
            'condition' => 'baik',
            'description' => 'Gedung pertemuan dan sarana olahraga warga desa.',
            'is_village_asset' => '1',
            'kib_type' => VillageFacility::KIB_C,
            'ownership_status' => VillageFacility::OWNERSHIP_APBDES,
            'register_code' => 'KIB-C.004.2024',
            'surface_area' => 850.50,
            'acquisition_year' => 2024,
            'asset_value' => 550000000,
        ];

        $response = $this->actingAs($this->superadmin)->post('/admin/facilities', $payload);
        $response->assertRedirect('/admin/facilities');

        $this->assertDatabaseHas('village_facilities', [
            'name' => 'Gedung Serbaguna Desa Sukamaju',
            'is_village_asset' => 1,
            'kib_type' => VillageFacility::KIB_C,
            'ownership_status' => VillageFacility::OWNERSHIP_APBDES,
            'register_code' => 'KIB-C.004.2024',
            'surface_area' => 850.50,
            'acquisition_year' => 2024,
            'asset_value' => 550000000,
        ]);

        $created = VillageFacility::where('register_code', 'KIB-C.004.2024')->firstOrFail();

        // Cek Audit Log tercipta
        $this->assertDatabaseHas('audit_logs', [
            'event_name' => 'VillageFacilityCreated',
            'entity_type' => 'villagefacility',
            'entity_id' => (string) $created->id,
            'actor_id' => $this->superadmin->id,
        ]);
    }

    /**
     * Test admin dapat memperbarui atribut aset desa dan perubahan tercatat di audit log.
     */
    public function test_admin_can_update_facility_asset_attributes(): void
    {
        $facility = VillageFacility::where('register_code', 'KIB-C.001.1985')->firstOrFail();

        $response = $this->actingAs($this->superadmin)->put("/admin/facilities/{$facility->id}", [
            'name' => 'Kantor Kepala Desa Sukamaju (Renovasi Gedung Utama)',
            'category' => 'pemerintahan',
            'latitude' => $facility->latitude,
            'longitude' => $facility->longitude,
            'address' => 'Jl. Raya Desa Sukamaju No. 01 Gedung Baru',
            'condition' => 'baik',
            'is_village_asset' => '1',
            'kib_type' => VillageFacility::KIB_C,
            'ownership_status' => VillageFacility::OWNERSHIP_TKD,
            'register_code' => 'KIB-C.001.1985',
            'surface_area' => 720.00,
            'acquisition_year' => 1985,
            'asset_value' => 600000000,
        ]);

        $response->assertRedirect('/admin/facilities');

        $this->assertDatabaseHas('village_facilities', [
            'id' => $facility->id,
            'name' => 'Kantor Kepala Desa Sukamaju (Renovasi Gedung Utama)',
            'surface_area' => 720.00,
            'asset_value' => 600000000,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'event_name' => 'VillageFacilityUpdated',
            'entity_type' => 'villagefacility',
            'entity_id' => (string) $facility->id,
            'actor_id' => $this->superadmin->id,
        ]);
    }

    /**
     * Test admin dapat menghapus fasilitas dan event audit tercatat.
     */
    public function test_admin_can_delete_facility(): void
    {
        $facility = VillageFacility::create([
            'name' => 'Pos Ronda Sementara',
            'category' => 'infrastruktur',
            'latitude' => -6.919999,
            'longitude' => 107.619999,
            'condition' => 'rusak_berat',
            'is_village_asset' => false,
        ]);

        $response = $this->actingAs($this->superadmin)->delete("/admin/facilities/{$facility->id}");
        $response->assertRedirect('/admin/facilities');

        $this->assertDatabaseMissing('village_facilities', [
            'id' => $facility->id,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'event_name' => 'VillageFacilityDeleted',
            'entity_type' => 'villagefacility',
            'entity_id' => (string) $facility->id,
            'actor_id' => $this->superadmin->id,
        ]);
    }

    /**
     * Test halaman publik peta Web GIS menampilkan atribut yuridis aset desa pada tema Default.
     */
    public function test_public_map_renders_facilities_and_permendagri_asset_details_default_theme(): void
    {
        Setting::set('active_theme', 'default');

        $response = $this->get('/peta');
        $response->assertStatus(200);
        $response->assertSee('Peta Digital & Fasilitas Desa');
        $response->assertSee('Inventarisasi Aset (Permendagri 1/2016)');
        $response->assertSee('Hanya Aset Desa');
        $response->assertSee('KIB C: Gedung dan Bangunan');
        $response->assertSee('Kantor Kepala Desa Sukamaju');
    }

    /**
     * Test halaman publik peta Web GIS menampilkan atribut yuridis aset desa pada tema Emerald.
     */
    public function test_public_map_renders_facilities_and_permendagri_asset_details_emerald_theme(): void
    {
        Setting::set('active_theme', 'emerald');

        $response = $this->get('/peta');
        $response->assertStatus(200);
        $response->assertSee('Peta Digital & Fasilitas Desa', false);
        $response->assertSee('Inventarisasi Aset (Permendagri 1/2016)');
        $response->assertSee('Hanya Aset Desa');
        $response->assertSee('KIB A: Tanah');
        $response->assertSee('Kawasan Wisata Agro');
    }

    /**
     * Test admin dapat mengunggah foto fasilitas/aset dan dibersihkan saat update atau delete.
     */
    public function test_admin_can_upload_facility_photo_and_cleanup_on_delete(): void
    {
        Storage::fake('public');

        $imageFile = UploadedFile::fake()->image('posyandu_dusun.jpg', 800, 600);

        // 1. Simpan fasilitas dengan file upload
        $response = $this->actingAs($this->superadmin)->post('/admin/facilities', [
            'name' => 'Gedung Posyandu Mawar 01',
            'category' => 'kesehatan',
            'latitude' => -6.920000,
            'longitude' => 107.620000,
            'condition' => 'baik',
            'is_village_asset' => true,
            'kib_type' => 'kib_c',
            'ownership_status' => 'tanah_kas_desa',
            'image_file' => $imageFile,
        ]);

        $response->assertRedirect('/admin/facilities');
        $this->assertDatabaseHas('village_facilities', [
            'name' => 'Gedung Posyandu Mawar 01',
        ]);

        $facility = VillageFacility::where('name', 'Gedung Posyandu Mawar 01')->firstOrFail();
        $this->assertNotNull($facility->image_url);
        $this->assertStringStartsWith('/storage/facilities/', $facility->image_url);

        $storedPath = str_replace('/storage/', '', $facility->image_url);
        Storage::disk('public')->assertExists($storedPath);

        // 2. Update dengan foto baru (foto lama harus dihapus)
        $newImageFile = UploadedFile::fake()->image('posyandu_dusun_baru.png', 800, 600);
        $updateResponse = $this->actingAs($this->superadmin)->put("/admin/facilities/{$facility->id}", [
            'name' => 'Gedung Posyandu Mawar 01 - Renovasi',
            'category' => 'kesehatan',
            'latitude' => -6.920000,
            'longitude' => 107.620000,
            'condition' => 'baik',
            'is_village_asset' => true,
            'kib_type' => 'kib_c',
            'ownership_status' => 'tanah_kas_desa',
            'image_file' => $newImageFile,
        ]);

        $updateResponse->assertRedirect('/admin/facilities');
        Storage::disk('public')->assertMissing($storedPath);

        $facility->refresh();
        $updatedStoredPath = str_replace('/storage/', '', $facility->image_url);
        Storage::disk('public')->assertExists($updatedStoredPath);

        // 3. Hapus fasilitas (foto juga harus dibersihkan dari disk storage)
        $deleteResponse = $this->actingAs($this->superadmin)->delete("/admin/facilities/{$facility->id}");
        $deleteResponse->assertRedirect('/admin/facilities');
        Storage::disk('public')->assertMissing($updatedStoredPath);
    }
}
