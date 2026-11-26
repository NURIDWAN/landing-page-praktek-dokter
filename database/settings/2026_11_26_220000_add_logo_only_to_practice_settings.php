<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if (! $this->migrator->exists('practice.logo_only')) {
            $this->migrator->add('practice.logo_only', false);
        }
    }
};
