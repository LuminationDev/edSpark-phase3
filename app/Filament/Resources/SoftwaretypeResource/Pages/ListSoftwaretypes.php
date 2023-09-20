<?php

namespace App\Filament\Resources\SoftwaretypeResource\Pages;

use App\Filament\Resources\SoftwaretypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSoftwaretypes extends ListRecords
{
    protected static string $resource = SoftwaretypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
