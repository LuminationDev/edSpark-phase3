<?php

namespace App\Filament\Resources\ProductbrandResource\Pages;

use App\Filament\Resources\ProductbrandResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductbrand extends CreateRecord
{
    protected static string $resource = ProductbrandResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Product brand created successfully';
    }
}
