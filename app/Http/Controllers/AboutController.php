<?php

namespace App\Http\Controllers;

use App\Services\PracticeDataService;
use App\Services\StructuredDataService;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __invoke(PracticeDataService $practiceData, StructuredDataService $structuredData): View
    {
        return view('pages.about', [
            'services' => $practiceData->activeServices(),
            'breadcrumbJsonLd' => $structuredData->forBreadcrumbs([
                ['name' => 'Beranda', 'url' => route('home')],
                ['name' => 'Tentang Praktek', 'url' => route('about')],
            ]),
        ]);
    }
}
