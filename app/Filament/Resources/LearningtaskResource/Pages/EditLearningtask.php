<?php

namespace App\Filament\Resources\LearningtaskResource\Pages;

use App\Filament\Resources\LearningtaskResource;
use App\Models\Advice;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

use Carbon\Carbon;
use Illuminate\Support\Str;


class EditLearningtask extends EditRecord
{
    protected static string $resource = LearningtaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDatabeforeFill(array $data): array
    {

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
        if (isset($data['tags'])) {
            $targetData->syncTags($data['tags']);
        }

        $data['updated_at'] = Carbon::now();
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
        return 'Learning task updated successfully';
    }
}
