<?php

namespace App\Filament\Resources\SpecialClosureResource\Pages;

use App\Filament\Resources\SpecialClosureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSpecialClosures extends ListRecords
{
    protected static string $resource = SpecialClosureResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
