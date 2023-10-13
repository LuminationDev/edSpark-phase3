<?php

namespace App\Filament\Resources\AdvicemoderationResource\Pages;

use App\Filament\Resources\AdvicemoderationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdvicemoderations extends ListRecords
{
    protected static string $resource = AdvicemoderationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
