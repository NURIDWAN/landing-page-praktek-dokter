<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Article extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity, SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_REVIEW = 'review';

    public const STATUS_SCHEDULED = 'scheduled';

    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'category_id',
        'author_id',
        'reviewed_by',
        'title',
        'slug',
        'excerpt',
        'body',
        'cover_image',
        'source_name',
        'source_url',
        'status',
        'published_at',
        'reviewed_at',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->where(function (Builder $q): void {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeScheduledDue(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SCHEDULED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if (extension_loaded('gd') || extension_loaded('imagick')) {
            $this->addMediaConversion('webp')
                ->format('webp')
                ->quality(82)
                ->nonQueued();
        }
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('cover');

        if ($media) {
            return $media->hasGeneratedConversion('webp')
                ? $media->getUrl('webp')
                : $media->getUrl();
        }

        if ($this->cover_image) {
            return str_starts_with($this->cover_image, 'http')
                ? $this->cover_image
                : asset('storage/'.$this->cover_image);
        }

        return null;
    }

    public function getAuthorNameAttribute(): string
    {
        return $this->reviewer?->name
            ?? $this->author?->name
            ?? 'dr. Siti Mudiroh';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'slug', 'category_id', 'status', 'published_at', 'reviewed_by', 'source_name'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('articles');
    }
}
