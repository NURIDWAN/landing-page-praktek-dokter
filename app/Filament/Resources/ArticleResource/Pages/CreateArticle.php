<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['author_id'] = Auth::id();

        if (($data['status'] ?? null) === Article::STATUS_PUBLISHED) {
            $data['published_at'] ??= now();
            $data['reviewed_by'] ??= Auth::id();
            $data['reviewed_at'] ??= now();
        }

        return $data;
    }
}
