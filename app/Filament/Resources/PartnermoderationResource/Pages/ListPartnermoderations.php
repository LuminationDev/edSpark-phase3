<?php

namespace App\Filament\Resources\PartnermoderationResource\Pages;

use App\Filament\Resources\PartnermoderationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartnermoderations extends ListRecords
{
    protected static string $resource = PartnermoderationResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
