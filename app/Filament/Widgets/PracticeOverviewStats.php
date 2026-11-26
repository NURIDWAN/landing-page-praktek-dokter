<?php

namespace App\Filament\Widgets;

use App\Models\Announcement;
use App\Models\Article;
use App\Models\ContactMessage;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PracticeOverviewStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $unreadMessages = ContactMessage::unread()->count();
        $draftOrReview = Article::whereIn('status', [Article::STATUS_DRAFT, Article::STATUS_REVIEW])->count();
        $publishedArticles = Article::published()->count();
        $activeServices = Service::activeOrdered()->count();
        $activeAnnouncements = Announcement::activeNow()->count();

        return [
            Stat::make('Pesan Belum Dibaca', $unreadMessages)
                ->description('Dari formulir kontak situs')
                ->color($unreadMessages > 0 ? 'warning' : 'success'),
            Stat::make('Artikel Draft / Tinjauan', $draftOrReview)
                ->description("Total {$publishedArticles} artikel terbit")
                ->color('info'),
            Stat::make('Layanan Aktif', $activeServices)
                ->description($activeAnnouncements > 0 ? "{$activeAnnouncements} pengumuman aktif" : 'Tidak ada pengumuman aktif')
                ->color('success'),
        ];
    }
}
