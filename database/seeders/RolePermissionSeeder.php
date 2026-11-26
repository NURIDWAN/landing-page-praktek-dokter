<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Articles & Categories
            'ViewAny:Article', 'View:Article', 'Create:Article', 'Update:Article', 'Delete:Article', 'Restore:Article', 'ForceDelete:Article',
            'ViewAny:ArticleCategory', 'View:ArticleCategory', 'Create:ArticleCategory', 'Update:ArticleCategory', 'Delete:ArticleCategory',
            // Media
            'ViewAny:Media', 'View:Media', 'Delete:Media',
            // Operations & Public Content
            'ViewAny:OpeningHour', 'View:OpeningHour', 'Create:OpeningHour', 'Update:OpeningHour', 'Delete:OpeningHour',
            'ViewAny:SpecialClosure', 'View:SpecialClosure', 'Create:SpecialClosure', 'Update:SpecialClosure', 'Delete:SpecialClosure',
            'ViewAny:Service', 'View:Service', 'Create:Service', 'Update:Service', 'Delete:Service',
            'ViewAny:Page', 'View:Page', 'Create:Page', 'Update:Page', 'Delete:Page',
            'ViewAny:Announcement', 'View:Announcement', 'Create:Announcement', 'Update:Announcement', 'Delete:Announcement',
            'ViewAny:ContactMessage', 'View:ContactMessage', 'Update:ContactMessage', 'Delete:ContactMessage',
            // Settings, Users, Roles, Activity Log
            'View:ManagePracticeSettings',
            'ViewAny:User', 'View:User', 'Create:User', 'Update:User', 'Delete:User',
            'ViewAny:Role', 'View:Role', 'Create:Role', 'Update:Role', 'Delete:Role',
            'ViewAny:Activity', 'View:Activity',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions($permissions);

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'ViewAny:Article', 'View:Article', 'Create:Article', 'Update:Article', 'Delete:Article',
            'ViewAny:ArticleCategory', 'View:ArticleCategory', 'Create:ArticleCategory', 'Update:ArticleCategory', 'Delete:ArticleCategory',
            'ViewAny:Media', 'View:Media', 'Delete:Media',
            'ViewAny:OpeningHour', 'View:OpeningHour', 'Create:OpeningHour', 'Update:OpeningHour', 'Delete:OpeningHour',
            'ViewAny:SpecialClosure', 'View:SpecialClosure', 'Create:SpecialClosure', 'Update:SpecialClosure', 'Delete:SpecialClosure',
            'ViewAny:Service', 'View:Service', 'Create:Service', 'Update:Service', 'Delete:Service',
            'ViewAny:Page', 'View:Page', 'Create:Page', 'Update:Page', 'Delete:Page',
            'ViewAny:Announcement', 'View:Announcement', 'Create:Announcement', 'Update:Announcement', 'Delete:Announcement',
            'ViewAny:ContactMessage', 'View:ContactMessage', 'Update:ContactMessage', 'Delete:ContactMessage',
            'View:ManagePracticeSettings',
        ]);

        // Editor: ONLY Articles, Article Categories, and Media (no Settings, Users, Roles, OpeningHours, ContactMessages)
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editor->syncPermissions([
            'ViewAny:Article', 'View:Article', 'Create:Article', 'Update:Article',
            'ViewAny:ArticleCategory', 'View:ArticleCategory', 'Create:ArticleCategory', 'Update:ArticleCategory',
            'ViewAny:Media', 'View:Media',
        ]);

        $doctor = User::updateOrCreate(
            ['email' => 'dokter@praktekdoktersitimudiroh.id'],
            [
                'name' => 'dr. Siti Mudiroh',
                'password' => Hash::make('PraktekDokter2026!'),
                'email_verified_at' => now(),
            ]
        );
        $doctor->syncRoles([$superAdmin]);

        $staff = User::updateOrCreate(
            ['email' => 'staf@praktekdoktersitimudiroh.id'],
            [
                'name' => 'Staf Praktek',
                'password' => Hash::make('StafPraktek2026!'),
                'email_verified_at' => now(),
            ]
        );
        $staff->syncRoles([$admin]);

        $writer = User::updateOrCreate(
            ['email' => 'editor@praktekdoktersitimudiroh.id'],
            [
                'name' => 'Editor Edukasi',
                'password' => Hash::make('EditorEdukasi2026!'),
                'email_verified_at' => now(),
            ]
        );
        $writer->syncRoles([$editor]);
    }
}
