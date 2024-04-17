<?php

namespace App\Filament\Resources\SoftwareResource\Pages;

use App\Filament\Resources\SoftwareResource;
use App\Models\Software;
use App\Models\User;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EditSoftware extends EditRecord
{
    protected static string $resource = SoftwareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDatabeforeFill(array $data): array
    {
        // author manipulation
//        if (isset($data['author_id'])) {
//            $author = User::find($data['author_id']);
//            if ($author) {
//                $data['selected_author'] = $author->id;
//            } else {
//                $data['selected_author'] = null;
//            }
//        } else {
//            $data['selected_author'] = null;
//        }

        // tags related
        $record = parent::getRecord();
        $targetData = Software::find($record->id);
        $data['tags'] = $targetData->tags;

        if (isset($data['tags'])) {
            $tagNames = $data['tags']->pluck('name');
            $data['tags'] = $tagNames;
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_at'] = Carbon::now();
        // tags related
        $record = parent::getRecord();
        $targetData = Software::find($record->id);
        if(isset($data['tags'])){
            $targetData->syncTags($data['tags']);
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Software updated successfully';
    }
}
