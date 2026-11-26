<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;
use UnitEnum;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan & Akses';

    protected static ?string $navigationLabel = 'Log Aktivitas';

    protected static ?string $modelLabel = 'Log Aktivitas';

    protected static ?string $pluralModelLabel = 'Log Aktivitas Admin';

    protected static ?int $navigationSort = 4;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Perubahan')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('log_name')->label('Kategori Log'),
                        TextEntry::make('event')->label('Aksi'),
                        TextEntry::make('causer.name')->label('Pelaku')->placeholder('Sistem'),
                        TextEntry::make('created_at')->label('Waktu')->dateTime('d M Y H:i:s'),
                        TextEntry::make('subject_type')->label('Entitas'),
                        TextEntry::make('subject_id')->label('ID Entitas'),
                        TextEntry::make('description')->label('Deskripsi')->columnSpanFull(),
                        TextEntry::make('attribute_changes')
                            ->label('Perubahan Atribut')
                            ->formatStateUsing(fn ($state): string => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '-')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('log_name')
                    ->label('Kategori')
                    ->badge(),
                TextColumn::make('event')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('causer.name')
                    ->label('Pengguna')
                    ->placeholder('Sistem'),
                TextColumn::make('subject_type')
                    ->label('Data')
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-'),
                TextColumn::make('description')
                    ->label('Ringkasan')
                    ->limit(40),
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->label('Kategori')
                    ->options([
                        'articles' => 'Artikel',
                        'schedule' => 'Jadwal',
                        'services' => 'Layanan',
                        'pages' => 'Halaman',
                        'announcements' => 'Pengumuman',
                        'users' => 'Pengguna',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'view' => Pages\ViewActivity::route('/{record}'),
        ];
    }
}
