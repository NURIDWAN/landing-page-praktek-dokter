<?php

namespace App\Console\Commands;

use App\Models\ContactMessage;
use Illuminate\Console\Command;

class PruneContactMessagesCommand extends Command
{
    protected $signature = 'contact-messages:prune {--months=12 : Batas retensi pesan dalam bulan}';

    protected $description = 'Hapus pesan kontak yang melewati masa retensi UU PDP.';

    public function handle(): int
    {
        $months = max(1, (int) $this->option('months'));
        $cutoff = now()->subMonths($months);

        $deleted = ContactMessage::where('created_at', '<=', $cutoff)->delete();

        $this->info("Berhasil menghapus {$deleted} pesan lebih lama dari {$months} bulan.");

        return self::SUCCESS;
    }
}
