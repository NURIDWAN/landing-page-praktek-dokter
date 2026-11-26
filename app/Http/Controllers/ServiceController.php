<?php

namespace App\Http\Controllers;

use App\Services\PracticeDataService;
use App\Services\StructuredDataService;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(PracticeDataService $practiceData, StructuredDataService $structuredData): View
    {
        return view('pages.services', [
            'services' => $practiceData->activeServices(),
            'breadcrumbJsonLd' => $structuredData->forBreadcrumbs([
                ['name' => 'Beranda', 'url' => route('home')],
                ['name' => 'Layanan', 'url' => route('services.index')],
            ]),
        ]);
    }
}
