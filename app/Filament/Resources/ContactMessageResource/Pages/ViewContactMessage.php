<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record->read_at === null) {
            $this->record->update(['read_at' => now()]);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('update_notes')
                ->label('Catatan & Tandai Selesai')
                ->fillForm(['admin_notes' => $this->record->admin_notes])
                ->schema([
                    Textarea::make('admin_notes')
                        ->label('Catatan Tindak Lanjut Staf')
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'admin_notes' => $data['admin_notes'],
                        'read_at' => $this->record->read_at ?? now(),
                        'handled_at' => $this->record->handled_at ?? now(),
                        'handled_by' => Auth::id(),
                    ]);
                }),
        ];
    }
}
