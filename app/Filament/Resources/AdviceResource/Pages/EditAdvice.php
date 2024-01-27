<?php

namespace App\Filament\Resources\AdviceResource\Pages;

use App\Filament\Resources\AdviceResource;
use App\Models\Advice;
use App\Models\Label;
use App\Models\User;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;


class EditAdvice extends EditRecord
{
    protected static string $resource = AdviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDatabeforeFill(array $data): array
    {
        if (isset($data['author_id'])) {
            $author = User::find($data['author_id']);

            if ($author) {
                // Format the author data to match the format used in the select options

                $data['selected_author'] = $author->id;
            } else {
                // Handle the case where the specified author_id does not exist
                $data['selected_author'] = null;
            }
        } else {
            // Handle the case where 'author_id' is not present in the data
            $data['selected_author'] = null;
        }

        $record = parent::getRecord();
        $targetData = Advice::find($record->id);
        $data['tags'] = $targetData->tags;

        if (isset($data['tags'])) {
            $tagNames = $data['tags']->pluck('name');
            $data['tags'] = $tagNames;
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = parent::getRecord();
        $targetData = Advice::find($record->id);
        if(isset($data['tags'])){
            $targetData->syncTags($data['tags']);
        }

        $data['post_modified'] = Carbon::now();
        return $data;
    }

    public static function getTemplateName($class): string
    {
        return Str::of($class)->afterLast('\\')->snake()->toString();
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Advice updated successfully';
    }
}
