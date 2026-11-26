<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Page extends Model
{
    use LogsActivity;

    protected static function booted(): void
    {
        static::saving(function (Page $page): void {
            if (filled($page->slug)) {
                $page->slug = Str::slug($page->slug);
            }
        });
    }

    protected $fillable = [
        'title',
        'slug',
        'body',
        'meta_title',
        'meta_description',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'slug', 'is_published'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('pages');
    }
}
