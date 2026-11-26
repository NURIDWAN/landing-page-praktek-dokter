<?php

namespace App\Filament\Resources\SpecialClosureResource\Pages;

use App\Filament\Resources\SpecialClosureResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSpecialClosure extends EditRecord
{
    protected static string $resource = SpecialClosureResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
