<?php

namespace App\Filament\Resources\LearningtaskResource\Pages;

use App\Filament\Resources\AdviceResource\Pages\EditAdvice;
use App\Filament\Resources\LearningtaskResource;

class EditLearningtask extends EditAdvice
{
    protected static string $resource = LearningtaskResource::class;


    protected function getSavedNotificationTitle(): ?string
    {
        return 'Learning task updated successfully';
    }
}
