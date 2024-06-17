<?php

namespace App\Filament\Resources\SurveyDownloadResource\Pages;

use App\Filament\Resources\SurveyDownloadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyDownloads extends ListRecords
{
    protected static string $resource = SurveyDownloadResource::class;
    public function __construct()
    {
        $this->subheading = 'Select records with the checkbox and click "Bulk Action" and choose "Download results"';
    }

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
