<?php

namespace App\Http\Controllers;

use App\Services\PracticeDataService;
use App\Services\StructuredDataService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(PracticeDataService $practiceData, StructuredDataService $structuredData): View
    {
        $settings = $practiceData->settings();
        $openingHours = $practiceData->openingHours();

        return view('pages.home', [
            'services' => $practiceData->activeServices(),
            'latestArticles' => $practiceData->latestArticles(3),
            'openingHours' => $openingHours,
            'upcomingClosures' => $practiceData->upcomingSpecialClosures(),
            'clinicJsonLd' => $structuredData->forClinic($settings, $openingHours),
        ]);
    }
}
