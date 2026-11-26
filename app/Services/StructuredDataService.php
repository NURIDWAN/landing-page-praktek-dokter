<?php

namespace App\Services;

use Illuminate\Support\Collection;

class StructuredDataService
{
    public function __construct(
        private readonly ScheduleService $scheduleService,
    ) {}

    /**
     * @param  Collection<int, object>  $openingHours
     * @return array<string, mixed>
     */
    public function forClinic(object $settings, Collection $openingHours): array
    {
        $clinicUrl = route('home');

        $clinic = [
            '@type' => ['MedicalClinic', 'Physician', 'LocalBusiness'],
            '@id' => $clinicUrl.'#clinic',
            'name' => $settings->practice_name,
            'description' => $settings->default_meta_description,
            'url' => $clinicUrl,
            'telephone' => $settings->phone,
            'image' => str_starts_with((string) $settings->hero_image, 'http') ? $settings->hero_image : url((string) $settings->hero_image),
            'medicalSpecialty' => 'GeneralPractice',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $settings->address,
                'addressLocality' => 'Curug',
                'addressRegion' => 'Banten',
                'postalCode' => '15810',
                'addressCountry' => 'ID',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $settings->latitude,
                'longitude' => (float) $settings->longitude,
            ],
            'openingHoursSpecification' => $this->scheduleService->openingHoursSpecification($openingHours),
            'areaServed' => ['Binong', 'Curug', 'Aryana Karawaci', 'Kabupaten Tangerang'],
        ];

        $sameAs = array_values(array_filter([
            $settings->instagram_url,
            $settings->facebook_url,
            $settings->maps_url,
        ]));
        if ($sameAs !== []) {
            $clinic['sameAs'] = $sameAs;
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => [$clinic],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function forArticle(object $article, object $settings): array
    {
        $articleUrl = route('articles.show', $article->slug);

        $breadcrumbs = [
            ['name' => 'Beranda', 'url' => route('home')],
            ['name' => 'Edukasi', 'url' => route('articles.index')],
        ];
        if ($article->category) {
            $breadcrumbs[] = [
                'name' => $article->category->name,
                'url' => route('articles.index', ['kategori' => $article->category->slug]),
            ];
        }
        $breadcrumbs[] = ['name' => $article->title, 'url' => $articleUrl];

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Article',
                    'headline' => $article->title,
                    'description' => $article->meta_description ?: $article->excerpt,
                    'url' => $articleUrl,
                    'mainEntityOfPage' => $articleUrl,
                    'image' => ($rawImg = ($article->cover_image_url ?: $settings->hero_image)) && str_starts_with((string) $rawImg, 'http')
                        ? $rawImg
                        : url((string) $rawImg),
                    'datePublished' => $article->published_at?->toIso8601String(),
                    'dateModified' => $article->updated_at?->toIso8601String(),
                    'author' => [
                        '@type' => 'Person',
                        'name' => $article->author_name ?? $settings->doctor_name,
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => $settings->practice_name,
                    ],
                    'citation' => $article->source_url ?: $article->source_name,
                ],
                $this->breadcrumbSchema($breadcrumbs),
            ],
        ];
    }

    /**
     * @param  array<int, array{name: string, url: string}>  $items
     * @return array<string, mixed>
     */
    public function forBreadcrumbs(array $items): array
    {
        return [
            '@context' => 'https://schema.org',
            ...$this->breadcrumbSchema($items),
        ];
    }

    /**
     * @param  array<int, array{name: string, url: string}>  $items
     * @return array<string, mixed>
     */
    private function breadcrumbSchema(array $items): array
    {
        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->values()->map(fn (array $item, int $idx): array => [
                '@type' => 'ListItem',
                'position' => $idx + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ])->all(),
        ];
    }
}
