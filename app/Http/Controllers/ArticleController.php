<?php

namespace App\Http\Controllers;

use App\Services\PracticeDataService;
use App\Services\StructuredDataService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request, PracticeDataService $practiceData, StructuredDataService $structuredData): View
    {
        $categorySlug = $request->string('kategori')->trim()->toString() ?: null;
        $search = $request->string('q')->trim()->toString() ?: null;

        return view('pages.articles.index', [
            'articles' => $practiceData->paginateArticles($categorySlug, $search, 9),
            'categories' => $practiceData->articleCategories(),
            'activeCategory' => $categorySlug,
            'searchQuery' => $search,
            'breadcrumbJsonLd' => $structuredData->forBreadcrumbs([
                ['name' => 'Beranda', 'url' => route('home')],
                ['name' => 'Edukasi', 'url' => route('articles.index')],
            ]),
        ]);
    }

    public function show(string $slug, PracticeDataService $practiceData, StructuredDataService $structuredData): View
    {
        $article = $practiceData->findPublishedArticle($slug);
        abort_if(! $article, 404);

        $relatedArticles = $practiceData->latestArticles(4)
            ->reject(fn ($item) => $item->slug === $article->slug)
            ->take(3);

        return view('pages.articles.show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'articleJsonLd' => $structuredData->forArticle($article, $practiceData->settings()),
        ]);
    }
}
