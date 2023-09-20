<?php

namespace App\Filament\Resources\EventtypeResource\Pages;

use App\Filament\Resources\EventtypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventtypes extends ListRecords
{
    protected static string $resource = EventtypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
