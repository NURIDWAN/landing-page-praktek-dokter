<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\PracticeDataService;
use Database\Seeders\PracticeContentSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class FilamentAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RolePermissionSeeder::class, PracticeContentSeeder::class]);
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        PracticeDataService::clearPublicCache();
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/articles')->assertRedirect('/admin/login');
    }

    public function test_super_admin_can_access_all_admin_pages_and_resources(): void
    {
        $doctor = User::where('email', 'dokter@praktekdoktersitimudiroh.id')->firstOrFail();

        $this->actingAs($doctor);

        foreach ([
            '/admin',
            '/admin/manage-practice-settings',
            '/admin/opening-hours',
            '/admin/special-closures',
            '/admin/services',
            '/admin/articles',
            '/admin/article-categories',
            '/admin/pages',
            '/admin/announcements',
            '/admin/contact-messages',
            '/admin/media',
            '/admin/users',
            '/admin/shield/roles',
            '/admin/activities',
        ] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_admin_can_access_operational_resources_but_not_users_roles_or_activity_log(): void
    {
        $staff = User::where('email', 'staf@praktekdoktersitimudiroh.id')->firstOrFail();

        $this->actingAs($staff);

        foreach ([
            '/admin',
            '/admin/manage-practice-settings',
            '/admin/opening-hours',
            '/admin/services',
            '/admin/articles',
            '/admin/contact-messages',
        ] as $uri) {
            $this->get($uri)->assertOk();
        }

        foreach ([
            '/admin/users',
            '/admin/shield/roles',
            '/admin/activities',
        ] as $forbiddenUri) {
            $this->get($forbiddenUri)->assertForbidden();
        }
    }

    public function test_editor_can_only_access_articles_categories_and_media(): void
    {
        $editor = User::where('email', 'editor@praktekdoktersitimudiroh.id')->firstOrFail();

        $this->actingAs($editor);

        foreach ([
            '/admin',
            '/admin/articles',
            '/admin/article-categories',
            '/admin/media',
        ] as $allowedUri) {
            $this->get($allowedUri)->assertOk();
        }

        foreach ([
            '/admin/manage-practice-settings',
            '/admin/users',
            '/admin/shield/roles',
            '/admin/activities',
            '/admin/opening-hours',
            '/admin/contact-messages',
            '/admin/services',
        ] as $forbiddenUri) {
            $this->get($forbiddenUri)->assertForbidden();
        }
    }
}
