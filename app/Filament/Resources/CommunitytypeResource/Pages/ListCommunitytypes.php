<?php

namespace App\Filament\Resources\CommunitytypeResource\Pages;

use App\Filament\Resources\CommunitytypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommunitytypes extends ListRecords
{
    protected static string $resource = CommunitytypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
