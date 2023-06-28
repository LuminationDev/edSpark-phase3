<?php

namespace App\Filament\Resources\PartnerprofileResource\Pages;

use App\Filament\Resources\PartnerprofileResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartnerprofiles extends ListRecords
{
    protected static string $resource = PartnerprofileResource::class;

    protected function getActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
