<?php

namespace App\Filament\Resources\PartnersResource\Pages;

use App\Filament\Resources\PartnersResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class EditPartners extends EditRecord
{
    protected static string $resource = PartnersResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDatabeforeFill(array $data): array
    {
        $user = User::findOrFail($data['user_id']);

        $data['full_name'] = $user->full_name;
        $data['display_name'] = $user->display_name;
        $data['password'] = $user->password;
        $data['email'] = $user->email;
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
            'full_name' => $data['full_name'],
            'display_name' => $data['display_name'],
            'email' => $data['email'],

        ]);
        // update partner's table
        $record->update([
            'name' => $data['full_name'],
            'email' => $data['email']
        ]);
        // return data
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
