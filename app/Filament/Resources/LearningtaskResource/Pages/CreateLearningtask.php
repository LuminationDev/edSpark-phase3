<?php

namespace App\Filament\Resources\LearningtaskResource\Pages;

use App\Filament\Resources\AdviceResource\Pages\CreateAdvice;
use App\Filament\Resources\LearningtaskResource;

class CreateLearningtask extends CreateAdvice
{

    protected static string $resource = LearningtaskResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Learning task created successfully';
    }
}
