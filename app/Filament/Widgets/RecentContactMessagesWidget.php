<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentContactMessagesWidget extends TableWidget
{
    protected static ?string $heading = 'Pesan Pasien Terbaru';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can('ViewAny:ContactMessage') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ContactMessage::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('name')->label('Nama')->weight('semibold'),
                TextColumn::make('phone')->label('No. HP'),
                TextColumn::make('message')->label('Pesan')->limit(55),
                IconColumn::make('read_at')
                    ->label('Dibaca')
                    ->boolean()
                    ->getStateUsing(fn (ContactMessage $record): bool => $record->read_at !== null),
                TextColumn::make('created_at')->label('Masuk')->since(),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('Lihat')
                    ->url(fn (ContactMessage $record): string => ContactMessageResource::getUrl('view', ['record' => $record])),
            ])
            ->paginated(false);
    }
}
