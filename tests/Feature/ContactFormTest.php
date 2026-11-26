<?php

namespace Tests\Feature;

use App\Mail\NewContactMessageMail;
use App\Models\ContactMessage;
use App\Services\PracticeDataService;
use Database\Seeders\PracticeContentSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RolePermissionSeeder::class, PracticeContentSeeder::class]);
        PracticeDataService::clearPublicCache();
    }

    public function test_contact_form_stores_message_hashes_ip_and_queues_mail(): void
    {
        Mail::fake();

        $response = $this->post('/kontak', [
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'message' => 'Apakah praktek buka sore ini pukul 17.00 WIB?',
            'privacy_agreed' => '1',
            'website_url' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('contact_success');

        $msg = ContactMessage::firstOrFail();
        $this->assertSame('Budi Santoso', $msg->name);
        $this->assertNotSame('127.0.0.1', $msg->ip_hash);
        $this->assertSame(64, strlen($msg->ip_hash));
        $this->assertNotNull($msg->privacy_agreed_at);

        Mail::assertQueued(NewContactMessageMail::class, function (NewContactMessageMail $mail) use ($msg) {
            return $mail->contactMessage->id === $msg->id;
        });
    }

    public function test_contact_form_requires_privacy_consent_and_rejects_honeypot(): void
    {
        Mail::fake();

        $this->post('/kontak', [
            'name' => 'Siti',
            'phone' => '081234567890',
            'message' => 'Menanyakan jadwal sore hari ini.',
            'website_url' => '',
        ])->assertSessionHasErrors('privacy_agreed');

        $this->post('/kontak', [
            'name' => 'Spam Bot',
            'phone' => '081234567890',
            'message' => 'Pesan spam otomatis dari bot.',
            'privacy_agreed' => '1',
            'website_url' => 'https://spam.example.com',
        ])->assertSessionHasErrors('website_url');

        $this->assertDatabaseCount('contact_messages', 0);
        Mail::assertNothingQueued();
    }

    public function test_contact_form_is_rate_limited(): void
    {
        Mail::fake();

        for ($i = 1; $i <= 5; $i++) {
            $this->post('/kontak', [
                'name' => "Pengunjung {$i}",
                'phone' => '081234567890',
                'message' => 'Menanyakan jadwal praktek hari ini.',
                'privacy_agreed' => '1',
            ])->assertRedirect();
        }

        $this->post('/kontak', [
            'name' => 'Pengunjung 6',
            'phone' => '081234567890',
            'message' => 'Menanyakan jadwal praktek hari ini.',
            'privacy_agreed' => '1',
        ])->assertStatus(429);
    }
}
