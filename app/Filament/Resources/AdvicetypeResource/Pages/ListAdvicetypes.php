<?php

namespace App\Filament\Resources\AdvicetypeResource\Pages;

use App\Filament\Resources\AdvicetypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdvicetypes extends ListRecords
{
    protected static string $resource = AdvicetypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
