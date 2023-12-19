<?php

namespace App\Filament\Resources\PartnersResource\Pages;

use App\Filament\Resources\PartnersResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Str;


class CreatePartners extends CreateRecord
{
    protected static string $resource = PartnersResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $user = User::create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'display_name' => $data['display_name'],
            'password' => $data['password'],
            'status' => 'Active',
            'remember_token' => Str::random(15),
            'role_id' => 23 // TODO:: CHANGE IN THE FUTURE PARTNER ROLE ID
        ]);

        $partner = $user->partner()->create([
            'name' => $data['full_name'],
            'email' => $data['email'],

        ]);

        return $partner;
        // return static::getModel()::create($data);

    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Partner created successfully';
    }
}
