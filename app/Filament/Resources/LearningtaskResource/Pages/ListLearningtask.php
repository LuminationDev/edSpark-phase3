<?php

namespace App\Filament\Resources\LearningtaskResource\Pages;

use App\Filament\Resources\AdviceResource\Pages\ListAdvice;
use App\Filament\Resources\LearningtaskResource;
use Filament\Pages\Actions;

class ListLearningtask extends ListAdvice
{
    protected static string $resource = LearningtaskResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
