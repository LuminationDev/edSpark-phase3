<?php

namespace App\Filament\Resources\SchoolmoderationResource\Pages;
use App\Models\School;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\SchoolmoderationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchoolmoderation extends EditRecord
{
    protected static string $resource = SchoolmoderationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
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
        School::where('school_id', $record->school_id)
            ->where('id', '!=', $record->id)
            ->where('status', '!=', 'Draft')
            ->update(['status' => 'Archived']);

        return $record;
    }

}
