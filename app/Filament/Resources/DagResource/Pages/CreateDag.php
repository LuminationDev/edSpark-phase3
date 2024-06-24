<?php

namespace App\Filament\Resources\DagResource\Pages;

use App\Filament\Resources\AdviceResource\Pages\CreateAdvice;
use App\Filament\Resources\DagResource;

class CreateDag extends CreateAdvice
{
    protected static string $resource = DagResource::class;


    protected function getCreatedNotificationTitle(): ?string
    {
        return 'DAG created successfully';
    }
}
