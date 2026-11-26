<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UpcomingScheduleAndDraftsWidget extends TableWidget
{
    protected static ?string $heading = 'Artikel Draft, Tinjauan & Terjadwal';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can('ViewAny:Article') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Article::query()
                    ->whereIn('status', [Article::STATUS_DRAFT, Article::STATUS_REVIEW, Article::STATUS_SCHEDULED])
                    ->latest('updated_at')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('title')->label('Judul Artikel')->limit(55)->weight('semibold'),
                TextColumn::make('category.name')->label('Kategori')->badge(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Article::STATUS_REVIEW => 'Ditinjau',
                        Article::STATUS_SCHEDULED => 'Terjadwal',
                        default => 'Draft',
                    }),
                TextColumn::make('published_at')->label('Jadwal Terbit')->dateTime('d M Y H:i')->placeholder('-'),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Kelola')
                    ->url(fn (Article $record): string => ArticleResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false);
    }
}
