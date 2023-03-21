<?php

namespace App\Filament\Resources\CommunitymoderationResource\Pages;

use App\Filament\Resources\CommunitymoderationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommunitymoderations extends ListRecords
{
    protected static string $resource = CommunitymoderationResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
