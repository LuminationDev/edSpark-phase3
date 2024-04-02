<?php

namespace App\Filament\Resources\PartnermoderationResource\Pages;

use App\Filament\Resources\PartnermoderationResource;
use App\Helpers\JsonHelper;
use App\Models\Partnerprofile;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPartnermoderation extends EditRecord
{
    protected static string $resource = PartnermoderationResource::class;

    protected function mutateFormDatabeforeFill(array $data): array
    {
        $data['content'] = JsonHelper::safelyDecodeString($data['content']);
        $data['motto'] = JsonHelper::safelyDecodeString($data['motto']);
        $data['introduction'] = JsonHelper::safelyDecodeString($data['introduction']);


        return $data;
    }

    protected function getHeaderActions(): array
    {
        $baseUrl = env('APP_URL');

        return [
            Action::make('preview')
                ->url(fn ($record) => rtrim($baseUrl, '/') . '/partner/'. $record->user_id . '?preview=true&source=filament')
                ->openUrlInNewTab()        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Partner profile moderated successfully';
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if($data['status'] == 'Published'){
            Partnerprofile::where('partner_id', $record->partner_id)
                ->where('id', '!=', $record->id)
                ->where('status', '!=', 'Draft')
                ->update(['status' => 'Archived']);
        }
        $record->update($data);



        return $record;
    }
}
