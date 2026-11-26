<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ContactMessage;
use App\Services\PracticeDataService;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Database\Seeders\PracticeContentSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleAndCommandsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RolePermissionSeeder::class, PracticeContentSeeder::class]);
        PracticeDataService::clearPublicCache();
    }

    public function test_schedule_service_prioritizes_special_closures_over_regular_hours(): void
    {
        $service = app(ScheduleService::class);
        $openingHours = collect([
            (object) ['day_of_week' => 3, 'open_time' => '16:00:00', 'close_time' => '20:00:00', 'is_closed' => false, 'notes' => null],
        ]);
        $wednesday = Carbon::parse('2026-11-25 17:00:00', 'Asia/Jakarta');

        $regularOpen = $service->todayStatus($openingHours, collect(), $wednesday);
        $this->assertTrue($regularOpen->is_open_now);
        $this->assertSame('Sedang Buka', $regularOpen->badge_text);

        $specialCustom = collect([
            (object) [
                'date' => '2026-11-25',
                'title' => 'Jadwal Sore Maju',
                'reason' => 'Pelatihan dokter',
                'is_closed' => false,
                'custom_open_time' => '14:00:00',
                'custom_close_time' => '16:30:00',
            ],
        ]);
        $customStatus = $service->todayStatus($openingHours, $specialCustom, $wednesday);
        $this->assertFalse($customStatus->is_open_now);
        $this->assertTrue($customStatus->is_special);
        $this->assertSame('14.00 – 16.30 WIB', $customStatus->hours_text);
    }

    public function test_publish_scheduled_articles_command_publishes_due_articles_only(): void
    {
        $category = ArticleCategory::firstOrFail();

        $due = Article::create([
            'category_id' => $category->id,
            'title' => 'Artikel Jatuh Tempo',
            'slug' => 'artikel-jatuh-tempo',
            'excerpt' => 'Ringkasan',
            'body' => '<p>Isi</p>',
            'source_name' => 'Kemenkes RI',
            'status' => Article::STATUS_SCHEDULED,
            'published_at' => now()->subMinute(),
        ]);

        $future = Article::create([
            'category_id' => $category->id,
            'title' => 'Artikel Besok',
            'slug' => 'artikel-besok',
            'excerpt' => 'Ringkasan',
            'body' => '<p>Isi</p>',
            'source_name' => 'Kemenkes RI',
            'status' => Article::STATUS_SCHEDULED,
            'published_at' => now()->addDay(),
        ]);

        $this->artisan('articles:publish-scheduled')->assertSuccessful();

        $this->assertSame(Article::STATUS_PUBLISHED, $due->fresh()->status);
        $this->assertSame(Article::STATUS_SCHEDULED, $future->fresh()->status);
    }

    public function test_prune_contact_messages_command_deletes_only_messages_older_than_12_months(): void
    {
        $old = ContactMessage::create([
            'name' => 'Pasien Lama',
            'phone' => '08111111111',
            'message' => 'Pesan lama',
            'ip_hash' => str_repeat('a', 64),
            'privacy_agreed_at' => now()->subMonths(13),
        ]);
        $old->forceFill(['created_at' => now()->subMonths(13)])->saveQuietly();

        $recent = ContactMessage::create([
            'name' => 'Pasien Baru',
            'phone' => '08222222222',
            'message' => 'Pesan baru',
            'ip_hash' => str_repeat('b', 64),
            'privacy_agreed_at' => now()->subMonth(),
        ]);
        $recent->forceFill(['created_at' => now()->subMonth()])->saveQuietly();

        $this->artisan('contact-messages:prune')->assertSuccessful();

        $this->assertDatabaseMissing('contact_messages', ['id' => $old->id]);
        $this->assertDatabaseHas('contact_messages', ['id' => $recent->id]);
    }
}
