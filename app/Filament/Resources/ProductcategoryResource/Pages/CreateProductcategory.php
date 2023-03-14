<?php

namespace App\Filament\Resources\ProductcategoryResource\Pages;

use App\Filament\Resources\ProductcategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductcategory extends CreateRecord
{
    protected static string $resource = ProductcategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Product category created successfully';
    }
}
