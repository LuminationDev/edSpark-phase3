<?php

namespace App\Filament\Resources\PartnersResource\Pages;

use App\Filament\Resources\PartnersResource;
use App\Models\Partnerprofile;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Helpers\JsonHelper;

class EditPartners extends EditRecord
{
    protected static string $resource = PartnersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDatabeforeFill(array $data): array
    {
        $user = User::findOrFail($data['user_id']);
        $data['display_name'] = $user->display_name;
        $profile = Partnerprofile::where('user_id', $user->id)->where('status', 'Published')->latest()->first();
        $data['content'] = JsonHelper::safelyDecodeString($profile['content']);
        $data['cover_image'] = JsonHelper::safelyDecodeString($profile['cover_image']);
        $data['logo'] = JsonHelper::safelyDecodeString($profile['logo']);
        $data['motto'] = JsonHelper::safelyDecodeString($profile['motto']);
        $data['introduction'] = JsonHelper::safelyDecodeString($profile['introduction']);


//        $data['full_name'] = $user->full_name;
//        $data['password'] = $user->password;
//        $data['email'] = $user->email;
        // $data['user_id'] = $user->id;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // echo 'Mutate From Data Before Save is called';
        // dd($data);
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        // update user's table
        $user = User::where('id', $record->user_id)->update([
            'display_name' => $data['display_name'],
        ]);
        // update partner's table
        $record->update([
            'motto' => $data['motto'],
            'introduction' => $data['introduction'],
        ]);

        // When an admin edit PartnerProfile from the backend, edit the last published entry instead
        $profile = Partnerprofile::where('user_id', $record->user_id)->where('status', 'Published')->latest()->first();
        $profile->update([
            'motto' => JsonHelper::safelyEncodeData($data['motto']),
            'introduction' => JsonHelper::safelyEncodeData($data['introduction']),
            'content' => JsonHelper::safelyEncodeData($data['content']),
            'logo' => JsonHelper::safelyEncodeData($data['logo']),
            'cover_image' => JsonHelper::safelyEncodeData($data['cover_image']),
        ]);


        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Partner updated successfully';
    }
}
