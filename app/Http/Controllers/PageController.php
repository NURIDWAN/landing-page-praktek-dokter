<?php

namespace App\Http\Controllers;

use App\Services\PracticeDataService;
use Illuminate\Http\Request;
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

    public function fallback(Request $request, PracticeDataService $practiceData): View
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            abort(404);
        }

        $path = trim($request->path(), '/');

        if ($path === '' || str_contains($path, '/') || str_contains($path, '.')) {
            abort(404);
        }

        return $this->renderSlug($path, $practiceData);
    }

    private function renderSlug(string $slug, PracticeDataService $practiceData): View
    {
        $page = $practiceData->findPublishedPage(trim($slug, '/'));
        abort_if(! $page, 404);

        return view('pages.static', [
            'page' => $page,
        ]);
    }
}
