<?php

namespace App\Filament\Resources\ProductbrandResource\Pages;

use App\Filament\Resources\ProductbrandResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductbrands extends ListRecords
{
    protected static string $resource = ProductbrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
