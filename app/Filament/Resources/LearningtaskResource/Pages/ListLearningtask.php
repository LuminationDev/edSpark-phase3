<?php

namespace App\Filament\Resources\LearningtaskResource\Pages;

use App\Filament\Resources\AdviceResource;
use App\Filament\Resources\LabelResource;
use App\Filament\Resources\LearningtaskResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLearningtask extends ListRecords
{
    protected static string $resource = LearningtaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
