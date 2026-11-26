<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use App\Models\Page;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_page')
                ->label('Lihat Halaman')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url(fn (Page $record): string => route('pages.show', $record->slug), shouldOpenInNewTab: true)
                ->visible(fn (Page $record): bool => (bool) $record->is_published),
            DeleteAction::make(),
        ];
    }
}
