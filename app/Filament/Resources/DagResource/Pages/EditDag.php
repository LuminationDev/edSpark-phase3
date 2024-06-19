<?php

namespace App\Filament\Resources\DagResource\Pages;

use App\Filament\Resources\AdviceResource\Pages\EditAdvice;
use App\Filament\Resources\DagResource;

class EditDag extends EditAdvice
{
    protected static string $resource = DagResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return 'DAG updated successfully';
    }
}
