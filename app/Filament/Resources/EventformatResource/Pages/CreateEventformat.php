<?php

namespace App\Filament\Resources\EventformatResource\Pages;

use App\Filament\Resources\EventformatResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEventformat extends CreateRecord
{
    protected static string $resource = EventformatResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
