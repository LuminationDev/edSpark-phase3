<?php

namespace App\Filament\Resources\SurveyDownloadResource\Pages;

use App\Filament\Resources\SurveyDownloadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurveyDownload extends EditRecord
{
    protected static string $resource = SurveyDownloadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
