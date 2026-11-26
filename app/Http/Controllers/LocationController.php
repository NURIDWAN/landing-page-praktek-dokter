<?php

namespace App\Http\Controllers;

use App\Services\PracticeDataService;
use App\Services\StructuredDataService;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function __invoke(PracticeDataService $practiceData, StructuredDataService $structuredData): View
    {
        $settings = $practiceData->settings();
        $openingHours = $practiceData->openingHours();

        return view('pages.location', [
            'openingHours' => $openingHours,
            'upcomingClosures' => $practiceData->upcomingSpecialClosures(),
            'clinicJsonLd' => $structuredData->forClinic($settings, $openingHours),
            'breadcrumbJsonLd' => $structuredData->forBreadcrumbs([
                ['name' => 'Beranda', 'url' => route('home')],
                ['name' => 'Lokasi & Jadwal', 'url' => route('location')],
            ]),
        ]);
    }
}
