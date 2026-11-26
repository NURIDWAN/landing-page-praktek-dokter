<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Services\PracticeDataService;
use Illuminate\Console\Command;

class PublishScheduledArticlesCommand extends Command
{
    protected $signature = 'articles:publish-scheduled';

    protected $description = 'Terbitkan artikel terjadwal yang telah mencapai waktu publikasi.';

    public function handle(): int
    {
        $dueArticles = Article::scheduledDue()->get();

        if ($dueArticles->isEmpty()) {
            $this->info('Tidak ada artikel terjadwal yang perlu diterbitkan.');

            return self::SUCCESS;
        }

        foreach ($dueArticles as $article) {
            $article->update([
                'status' => Article::STATUS_PUBLISHED,
            ]);
        }

        PracticeDataService::clearPublicCache();

        $this->info("Berhasil menerbitkan {$dueArticles->count()} artikel terjadwal.");

        return self::SUCCESS;
    }
}
