<?php

namespace App\Filament\Resources\DagResource\Pages;

use App\Filament\Resources\AdviceResource\Pages\ListAdvice;
use App\Filament\Resources\DagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDags extends ListAdvice
{
    protected static string $resource = DagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
