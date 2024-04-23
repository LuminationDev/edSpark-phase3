<?php

namespace App\Filament\Resources\CatalogueversionResource\Pages;

use App\Filament\Resources\CatalogueversionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateCatalogueversion extends CreateRecord
{
    protected static string $resource = CatalogueversionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['is_active'] = false;
        return parent::mutateFormDataBeforeCreate($data);
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Create the main record.
        $record = parent::handleRecordCreation($data);
    }
}
