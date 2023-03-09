<?php

namespace App\Filament\Resources\AdvicetypeResource\Pages;

use App\Filament\Resources\AdvicetypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdvicetype extends CreateRecord
{
    protected static string $resource = AdvicetypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Advice type created successfully';
    }
}
