<?php

namespace App\Http\Controllers;

use App\Services\PracticeDataService;
use Illuminate\View\View;

class PageController extends Controller
{
    public function privacy(PracticeDataService $practiceData): View
    {
        return $this->renderSlug('kebijakan-privasi', $practiceData);
    }

    public function terms(PracticeDataService $practiceData): View
    {
        return $this->renderSlug('syarat-ketentuan', $practiceData);
    }

    public function show(string $slug, PracticeDataService $practiceData): View
    {
        return $this->renderSlug($slug, $practiceData);
    }

    private function renderSlug(string $slug, PracticeDataService $practiceData): View
    {
        $page = $practiceData->findPublishedPage($slug);
        abort_if(! $page, 404);

        return view('pages.static', [
            'page' => $page,
        ]);
    }
}
