<?php

namespace App\Filament\Resources\CatalogueversionResource\Pages;

use App\Filament\Resources\CatalogueversionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatalogueversion extends EditRecord
{
    protected static string $resource = CatalogueversionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
