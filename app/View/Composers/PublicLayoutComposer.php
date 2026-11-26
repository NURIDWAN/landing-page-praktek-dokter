<?php

namespace App\View\Composers;

use App\Services\PracticeDataService;
use Illuminate\View\View;

class PublicLayoutComposer
{
    public function __construct(
        private readonly PracticeDataService $practiceData,
    ) {}

    public function compose(View $view): void
    {
        $settings = $this->practiceData->settings();
        $whatsappNumber = preg_replace('/\D+/', '', (string) $settings->whatsapp);
        $whatsappMessage = rawurlencode((string) $settings->whatsapp_default_message);

        $view->with([
            'siteSettings' => $settings,
            'activeAnnouncement' => $this->practiceData->activeAnnouncement(),
            'todaySchedule' => $this->practiceData->todaySchedule(),
            'whatsappHref' => "https://wa.me/{$whatsappNumber}?text={$whatsappMessage}",
            'telHref' => 'tel:'.preg_replace('/\D+/', '', (string) $settings->phone_raw),
        ]);
    }
}
