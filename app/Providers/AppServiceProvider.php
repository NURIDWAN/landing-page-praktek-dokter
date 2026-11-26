<?php

namespace App\Providers;

use App\Listeners\ClearSettingsCacheListener;
use App\Models\Announcement;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\OpeningHour;
use App\Models\Page;
use App\Models\Service;
use App\Models\SpecialClosure;
use App\Observers\PublicCacheObserver;
use App\Policies\ActivityPolicy;
use App\Policies\MediaPolicy;
use App\Policies\RolePolicy;
use App\View\Composers\PublicLayoutComposer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;
use Spatie\LaravelSettings\Events\SettingsSaved;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('id');

        View::composer([
            'layouts.app',
            'pages.*',
            'components.seo-head',
            'components.medical-disclaimer',
        ], PublicLayoutComposer::class);

        foreach ([
            OpeningHour::class,
            SpecialClosure::class,
            Service::class,
            ArticleCategory::class,
            Article::class,
            Page::class,
            Announcement::class,
        ] as $modelClass) {
            $modelClass::observe(PublicCacheObserver::class);
        }

        Event::listen(SettingsSaved::class, ClearSettingsCacheListener::class);

        Gate::policy(Media::class, MediaPolicy::class);
        Gate::policy(Activity::class, ActivityPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
    }
}
