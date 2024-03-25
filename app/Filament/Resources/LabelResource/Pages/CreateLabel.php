<?php

namespace App\Filament\Resources\LabelResource\Pages;

use App\Filament\Resources\LabelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLabel extends CreateRecord
{
    protected static string $resource = LabelResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if(isset($data['new_type'])){
            $data['type'] = $data['new_type'];
        }

        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
