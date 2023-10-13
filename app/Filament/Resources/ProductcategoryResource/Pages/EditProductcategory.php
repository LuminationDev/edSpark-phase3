<?php

namespace App\Filament\Resources\ProductcategoryResource\Pages;

use App\Filament\Resources\ProductcategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductcategory extends EditRecord
{
    protected static string $resource = ProductcategoryResource::class;

    protected function getHeaderActions(): array
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
        return 'Product category updated successfully';
    }
}
