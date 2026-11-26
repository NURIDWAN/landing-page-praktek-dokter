<?php

namespace App\Listeners;

use App\Services\PracticeDataService;
use Spatie\LaravelSettings\Events\SettingsSaved;

class ClearSettingsCacheListener
{
    public function handle(SettingsSaved $event): void
    {
        PracticeDataService::clearPublicCache();
    }
}
