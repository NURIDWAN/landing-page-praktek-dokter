<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if (! $this->migrator->exists('practice.favicon')) {
            $this->migrator->add('practice.favicon', null);
        }
    }
};
