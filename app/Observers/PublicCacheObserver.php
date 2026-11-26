<?php

namespace App\Observers;

use App\Services\PracticeDataService;
use Illuminate\Database\Eloquent\Model;

class PublicCacheObserver
{
    public function saved(Model $model): void
    {
        PracticeDataService::clearPublicCache();
    }

    public function deleted(Model $model): void
    {
        PracticeDataService::clearPublicCache();
    }

    public function restored(Model $model): void
    {
        PracticeDataService::clearPublicCache();
    }

    public function forceDeleted(Model $model): void
    {
        PracticeDataService::clearPublicCache();
    }
}
