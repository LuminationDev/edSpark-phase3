<?php

namespace App\Filament\Resources\ProductbrandResource\Pages;

use App\Filament\Resources\ProductbrandResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductbrand extends EditRecord
{
    protected static string $resource = ProductbrandResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Product brand updated successfully';
    }
}
