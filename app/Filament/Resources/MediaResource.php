<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use UnitEnum;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|UnitEnum|null $navigationGroup = 'Konten Publik';

    protected static ?string $navigationLabel = 'Pustaka Media';

    protected static ?string $modelLabel = 'Media';

    protected static ?string $pluralModelLabel = 'Pustaka Media';

    protected static ?int $navigationSort = 5;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('preview')
                    ->label('Pratinjau')
                    ->state(fn (Media $record): string => $record->hasGeneratedConversion('webp') ? $record->getUrl('webp') : $record->getUrl())
                    ->square(),
                TextColumn::make('file_name')
                    ->label('Nama Berkas')
                    ->searchable()
                    ->limit(35),
                TextColumn::make('collection_name')
                    ->label('Koleksi')
                    ->badge(),
                TextColumn::make('mime_type')
                    ->label('Tipe'),
                TextColumn::make('size')
                    ->label('Ukuran')
                    ->formatStateUsing(fn (int $state): string => number_format($state / 1024, 1).' KB'),
                TextColumn::make('created_at')
                    ->label('Diunggah')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedia::route('/'),
        ];
    }
}
