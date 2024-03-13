<?php

namespace App\Filament\Resources\EventformatResource\Pages;

use App\Filament\Resources\EventformatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventformats extends ListRecords
{
    protected static string $resource = EventformatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}