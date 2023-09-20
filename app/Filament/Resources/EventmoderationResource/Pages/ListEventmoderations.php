<?php

namespace App\Filament\Resources\EventmoderationResource\Pages;

use App\Filament\Resources\EventmoderationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventmoderations extends ListRecords
{
    protected static string $resource = EventmoderationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
