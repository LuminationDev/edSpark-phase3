<?php

namespace App\Filament\Resources\SoftwaremoderationResource\Pages;

use App\Filament\Resources\SoftwaremoderationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSoftwaremoderations extends ListRecords
{
    protected static string $resource = SoftwaremoderationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
