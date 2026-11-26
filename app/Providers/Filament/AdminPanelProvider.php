<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\PracticeOverviewStats;
use App\Filament\Widgets\RecentContactMessagesWidget;
use App\Filament\Widgets\UpcomingScheduleAndDraftsWidget;
use App\Services\PracticeDataService;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Praktek Dokter Siti Mudiroh')
            ->favicon(function (): string {
                try {
                    return app(PracticeDataService::class)->settings()->favicon;
                } catch (\Throwable) {
                    return '/favicon.ico';
                }
            })
            ->maxContentWidth(Width::Full)
            ->colors([
                'primary' => Color::hex('#176b55'),
            ])
            ->navigationGroups([
                NavigationGroup::make('Konten Publik'),
                NavigationGroup::make('Operasional Praktek'),
                NavigationGroup::make('Komunikasi Pasien'),
                NavigationGroup::make('Pengaturan & Akses'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                PracticeOverviewStats::class,
                RecentContactMessagesWidget::class,
                UpcomingScheduleAndDraftsWidget::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('Pengaturan & Akses'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
