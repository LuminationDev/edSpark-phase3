<?php

namespace App\Filament\Resources\SchoolmoderationResource\Pages;

use App\Helpers\StatusHelpers;
use App\Models\School;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\SchoolmoderationResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditSchoolmoderation extends EditRecord
{
    protected static string $resource = SchoolmoderationResource::class;

    protected function getHeaderActions(): array
    {
        $baseUrl = env('APP_URL');
        return [
            Action::make('preview')
                ->url(fn ($record) => rtrim($baseUrl, '/') . '/schools/'. $record->name . '?preview=true&source=filament')
                ->openUrlInNewTab()
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'School moderated successfully';
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);
        if($data['status'] == \App\Helpers\StatusHelpers::PUBLISHED){
            School::where('school_id', $record->school_id)
                ->where('id', '!=', $record->id)
                ->where('status', '!=', StatusHelpers::DRAFT)
                ->update(['status' => StatusHelpers::ARCHIVED]);
        }


        return $record;
    }

}
