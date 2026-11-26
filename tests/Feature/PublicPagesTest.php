<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Service;
use App\Models\SpecialClosure;
use App\Services\PracticeDataService;
use App\Settings\PracticeSettings;
use Database\Seeders\PracticeContentSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RolePermissionSeeder::class, PracticeContentSeeder::class]);
        PracticeDataService::clearPublicCache();
    }

    public function test_all_public_pages_render_successfully(): void
    {
        foreach ([
            '/',
            '/layanan',
            '/tentang',
            '/lokasi-jadwal',
            '/artikel',
            '/artikel/kapan-demam-anak-perlu-diperiksakan-ke-dokter',
            '/kontak',
            '/kebijakan-privasi',
            '/syarat-ketentuan',
            '/sitemap.xml',
        ] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_settings_and_dynamic_content_updates_reflect_immediately(): void
    {
        $this->get('/')->assertOk()->assertSee('0853-1022-6518');

        $settings = app(PracticeSettings::class);
        $settings->phone = '0812-9999-8888';
        $settings->save();

        Service::create([
            'title' => 'Pemeriksaan Tekanan Darah Cepat',
            'slug' => 'pemeriksaan-tekanan-darah-cepat',
            'summary' => 'Pengecekan tensi dan konsultasi singkat.',
            'description' => 'Layanan pemeriksaan tekanan darah harian.',
            'icon' => 'stethoscope',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        Announcement::create([
            'title' => 'Libur Hari Ini',
            'message' => 'Pengumuman penting: praktek tutup sementara tanggal 30.',
            'is_active' => true,
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
        ]);

        SpecialClosure::create([
            'date' => now('Asia/Jakarta')->toDateString(),
            'title' => 'Libur Khusus Hari Ini',
            'reason' => 'Dokter menghadiri seminar medis.',
            'is_closed' => true,
        ]);

        $home = $this->get('/');
        $home->assertOk()
            ->assertSee('0812-9999-8888')
            ->assertSee('Pemeriksaan Tekanan Darah Cepat')
            ->assertSee('Pengumuman penting: praktek tutup sementara tanggal 30.')
            ->assertSee('Tutup Sementara');
    }

    public function test_expired_announcement_is_not_displayed(): void
    {
        Announcement::create([
            'title' => 'Pengumuman Kadaluarsa',
            'message' => 'Pengumuman lama yang tidak boleh tampil lagi.',
            'is_active' => true,
            'starts_at' => now()->subDays(5),
            'ends_at' => now()->subDay(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Pengumuman lama yang tidak boleh tampil lagi.');
    }

    public function test_unpublished_articles_are_hidden_from_public_and_sitemap(): void
    {
        $category = ArticleCategory::firstOrFail();

        Article::create([
            'category_id' => $category->id,
            'title' => 'Artikel Draft Rahasia',
            'slug' => 'artikel-draft-rahasia',
            'excerpt' => 'Ringkasan draft.',
            'body' => '<p>Isi draft</p>',
            'source_name' => 'Kemenkes RI',
            'status' => Article::STATUS_DRAFT,
        ]);

        Article::create([
            'category_id' => $category->id,
            'title' => 'Artikel Terjadwal Masa Depan',
            'slug' => 'artikel-terjadwal-masa-depan',
            'excerpt' => 'Ringkasan terjadwal.',
            'body' => '<p>Isi terjadwal</p>',
            'source_name' => 'Kemenkes RI',
            'status' => Article::STATUS_SCHEDULED,
            'published_at' => now()->addDays(2),
        ]);

        $this->get('/artikel/artikel-draft-rahasia')->assertNotFound();
        $this->get('/artikel/artikel-terjadwal-masa-depan')->assertNotFound();
        $this->get('/artikel')
            ->assertOk()
            ->assertDontSee('Artikel Draft Rahasia')
            ->assertDontSee('Artikel Terjadwal Masa Depan');

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('kapan-demam-anak-perlu-diperiksakan-ke-dokter')
            ->assertDontSee('artikel-draft-rahasia')
            ->assertDontSee('artikel-terjadwal-masa-depan');
    }

    public function test_article_detail_includes_reference_disclaimer_and_valid_json_ld(): void
    {
        $response = $this->get('/artikel/kapan-demam-anak-perlu-diperiksakan-ke-dokter');
        $response->assertOk()
            ->assertSee('Ikatan Dokter Anak Indonesia (IDAI)')
            ->assertSee('Catatan &amp; Disclaimer Medis', false);

        $html = $response->getContent();
        preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $matches);
        $this->assertNotEmpty($matches[1] ?? null);

        $json = json_decode($matches[1], true);
        $this->assertIsArray($json);
        $this->assertSame('Article', $json['@graph'][0]['@type']);
        $this->assertSame('BreadcrumbList', $json['@graph'][1]['@type']);

        $homeHtml = $this->get('/')->getContent();
        preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $homeHtml, $homeMatches);
        $this->assertNotEmpty($homeMatches[1] ?? null);

        $homeJson = json_decode($homeMatches[1], true);
        $this->assertIsArray($homeJson);
        $this->assertContains('MedicalClinic', $homeJson['@graph'][0]['@type']);
        $this->assertNotEmpty($homeJson['@graph'][0]['openingHoursSpecification']);
    }

    public function test_article_filter_and_search_work(): void
    {
        $this->get('/artikel?kategori=kesehatan-anak')
            ->assertOk()
            ->assertSee('Kapan Demam pada Anak Perlu Segera Diperiksakan ke Dokter?')
            ->assertDontSee('Mengenal Tekanan Darah Tinggi');

        $this->get('/artikel?q=Hipertensi')
            ->assertOk()
            ->assertSee('Mengenal Tekanan Darah Tinggi');
    }
}
