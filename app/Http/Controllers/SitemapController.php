<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Page;
use App\Services\PracticeDataService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $xml = Cache::remember('practice.sitemap.xml', PracticeDataService::CACHE_TTL_SECONDS, function (): string {
            $sitemap = Sitemap::create()
                ->add(Url::create(route('home'))->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
                ->add(Url::create(route('services.index'))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
                ->add(Url::create(route('about'))->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
                ->add(Url::create(route('location'))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
                ->add(Url::create(route('articles.index'))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
                ->add(Url::create(route('contact.index'))->setPriority(0.7)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
                ->add(Url::create(route('pages.privacy'))->setPriority(0.4)->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY))
                ->add(Url::create(route('pages.terms'))->setPriority(0.4)->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY));

            Article::published()
                ->orderByDesc('published_at')
                ->get()
                ->each(function (Article $article) use ($sitemap): void {
                    $url = Url::create(route('articles.show', $article->slug))
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY);

                    if ($article->updated_at) {
                        $url->setLastModificationDate($article->updated_at);
                    }

                    $sitemap->add($url);
                });

            Page::published()
                ->whereNotIn('slug', ['kebijakan-privasi', 'syarat-ketentuan'])
                ->get()
                ->each(function (Page $page) use ($sitemap): void {
                    $url = Url::create(route('pages.show', $page->slug))
                        ->setPriority(0.5)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY);

                    if ($page->updated_at) {
                        $url->setLastModificationDate($page->updated_at);
                    }

                    $sitemap->add($url);
                });

            return $sitemap->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
