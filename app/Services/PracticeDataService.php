<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\OpeningHour;
use App\Models\Page;
use App\Models\Service;
use App\Models\SpecialClosure;
use App\Settings\PracticeSettings;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PracticeDataService
{
    public const CACHE_TTL_SECONDS = 3600;

    private const CACHE_KEYS = [
        'practice.settings.v2',
        'practice.announcement.v1',
        'practice.opening_hours.v1',
        'practice.special_closures.v1',
        'practice.services.active.v1',
        'practice.article_categories.v1',
        'practice.articles.latest.3',
        'practice.sitemap.xml',
    ];

    public function __construct(
        private readonly ScheduleService $scheduleService,
        private readonly PracticeSettings $practiceSettings,
    ) {}

    public static function clearPublicCache(): void
    {
        foreach (self::CACHE_KEYS as $key) {
            Cache::forget($key);
        }
    }

    public function settings(): object
    {
        $data = Cache::remember('practice.settings.v2', self::CACHE_TTL_SECONDS, function (): array {
            $s = $this->practiceSettings;

            return [
                'practice_name' => $s->practice_name,
                'tagline' => $s->tagline,
                'hero_badge' => $s->hero_badge,
                'hero_title' => $s->hero_title,
                'hero_description' => $s->hero_description,
                'hero_primary_cta' => $s->hero_primary_cta,
                'hero_secondary_cta' => $s->hero_secondary_cta,
                'hero_image' => $this->resolveImage($s->hero_image, '/images/clinic-hero.webp'),
                'logo' => $s->logo ? $this->resolveImage($s->logo, '') : null,
                'logo_only' => (bool) $s->logo_only,
                'favicon' => $this->resolveImage($s->favicon, '/favicon.ico'),
                'doctor_name' => $s->doctor_name,
                'doctor_role' => $s->doctor_role,
                'doctor_bio' => $s->doctor_bio,
                'doctor_notes' => $s->doctor_notes,
                'doctor_photo' => $this->resolveImage($s->doctor_photo, '/images/doctor-profile.webp'),
                'sip_number' => $s->sip_number,
                'phone' => $s->phone,
                'phone_raw' => preg_replace('/\D+/', '', $s->phone),
                'whatsapp' => $s->whatsapp,
                'whatsapp_default_message' => $s->whatsapp_default_message,
                'email' => $s->email,
                'address' => $s->address,
                'service_area_title' => $s->service_area_title,
                'service_area_text' => $s->service_area_text,
                'maps_url' => $s->maps_url,
                'maps_embed_url' => $s->maps_embed_url,
                'latitude' => $s->latitude,
                'longitude' => $s->longitude,
                'google_rating_text' => $s->google_rating_text,
                'medical_disclaimer' => $s->medical_disclaimer,
                'default_meta_title' => $s->default_meta_title,
                'default_meta_description' => $s->default_meta_description,
                'instagram_url' => $s->instagram_url,
                'facebook_url' => $s->facebook_url,
            ];
        });

        return (object) $data;
    }

    public function activeAnnouncement(): ?object
    {
        $row = Cache::remember('practice.announcement.v1', 300, function (): ?array {
            $announcement = Announcement::activeNow()
                ->orderByDesc('starts_at')
                ->orderByDesc('id')
                ->first();

            if (! $announcement) {
                return null;
            }

            return [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'message' => $announcement->message,
                'link_label' => $announcement->link_label,
                'link_url' => $announcement->link_url,
            ];
        });

        return $row ? (object) $row : null;
    }

    /**
     * @return Collection<int, object>
     */
    public function openingHours(): Collection
    {
        $rows = Cache::remember('practice.opening_hours.v1', self::CACHE_TTL_SECONDS, function (): array {
            return OpeningHour::orderBy('day_of_week')
                ->get(['id', 'day_of_week', 'day_name', 'open_time', 'close_time', 'is_closed', 'notes'])
                ->toArray();
        });

        return collect($rows)->map(fn (array $row): object => (object) $row);
    }

    /**
     * @return Collection<int, object>
     */
    public function upcomingSpecialClosures(): Collection
    {
        $rows = Cache::remember('practice.special_closures.v1', 900, function (): array {
            return SpecialClosure::upcoming()
                ->get()
                ->map(fn (SpecialClosure $c): array => [
                    'id' => $c->id,
                    'date' => $c->date->toDateString(),
                    'title' => $c->title,
                    'reason' => $c->reason,
                    'is_closed' => $c->is_closed,
                    'custom_open_time' => $c->custom_open_time,
                    'custom_close_time' => $c->custom_close_time,
                ])
                ->all();
        });

        return collect($rows)->map(fn (array $row): object => (object) $row);
    }

    public function todaySchedule(): object
    {
        return $this->scheduleService->todayStatus(
            $this->openingHours(),
            $this->upcomingSpecialClosures(),
        );
    }

    /**
     * @return Collection<int, object>
     */
    public function activeServices(): Collection
    {
        $rows = Cache::remember('practice.services.active.v1', self::CACHE_TTL_SECONDS, function (): array {
            return Service::activeOrdered()
                ->get(['id', 'title', 'slug', 'summary', 'description', 'icon', 'sort_order', 'is_active'])
                ->toArray();
        });

        return collect($rows)->map(fn (array $row): object => (object) $row);
    }

    /**
     * @return Collection<int, object>
     */
    public function articleCategories(): Collection
    {
        $rows = Cache::remember('practice.article_categories.v1', self::CACHE_TTL_SECONDS, function (): array {
            return ArticleCategory::orderBy('sort_order')
                ->orderBy('name')
                ->withCount(['articles' => fn ($q) => $q->published()])
                ->get(['id', 'name', 'slug', 'description', 'sort_order'])
                ->toArray();
        });

        return collect($rows)->map(fn (array $row): object => (object) $row);
    }

    /**
     * @return Collection<int, object>
     */
    public function latestArticles(int $limit = 3): Collection
    {
        if ($limit === 3) {
            $rows = Cache::remember('practice.articles.latest.3', 900, function (): array {
                return Article::published()
                    ->with(['category', 'author', 'reviewer', 'media'])
                    ->orderByDesc('published_at')
                    ->limit(3)
                    ->get()
                    ->map(fn (Article $article): array => $this->serializeArticle($article))
                    ->all();
            });

            return collect($rows)->map(fn (array $row): object => $this->hydrateArticle($row));
        }

        return Article::published()
            ->with(['category', 'author', 'reviewer', 'media'])
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get()
            ->map(fn (Article $article): object => $this->hydrateArticle($this->serializeArticle($article)));
    }

    public function paginateArticles(?string $categorySlug = null, ?string $search = null, int $perPage = 9): LengthAwarePaginator
    {
        return Article::published()
            ->with(['category', 'author', 'reviewer', 'media'])
            ->when($categorySlug, function ($query, string $slug): void {
                $query->whereHas('category', fn ($q) => $q->where('slug', $slug));
            })
            ->when($search, function ($query, string $term): void {
                $query->where(function ($q) use ($term): void {
                    $q->where('title', 'like', "%{$term}%")
                        ->orWhere('excerpt', 'like', "%{$term}%")
                        ->orWhere('body', 'like', "%{$term}%");
                });
            })
            ->orderByDesc('published_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findPublishedArticle(string $slug): ?Article
    {
        return Article::published()
            ->with(['category', 'author', 'reviewer', 'media'])
            ->where('slug', $slug)
            ->first();
    }

    public function findPublishedPage(string $slug): ?Page
    {
        return Page::published()
            ->where('slug', $slug)
            ->first();
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeArticle(Article $article): array
    {
        return [
            'id' => $article->id,
            'title' => $article->title,
            'slug' => $article->slug,
            'excerpt' => $article->excerpt,
            'cover_image_url' => $article->cover_image_url,
            'source_name' => $article->source_name,
            'source_url' => $article->source_url,
            'author_name' => $article->author_name,
            'published_at' => $article->published_at?->toIso8601String(),
            'updated_at' => $article->updated_at?->toIso8601String(),
            'category' => $article->category ? [
                'id' => $article->category->id,
                'name' => $article->category->name,
                'slug' => $article->category->slug,
            ] : null,
        ];
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function hydrateArticle(array $row): object
    {
        return (object) [
            ...$row,
            'category' => $row['category'] ? (object) $row['category'] : null,
            'published_at' => $row['published_at'] ? Carbon::parse($row['published_at']) : null,
            'updated_at' => $row['updated_at'] ? Carbon::parse($row['updated_at']) : null,
        ];
    }

    private function resolveImage(?string $path, string $fallback): string
    {
        if (blank($path)) {
            return $fallback;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }

        return '/storage/'.ltrim($path, '/');
    }
}
