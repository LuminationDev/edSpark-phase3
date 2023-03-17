<?php

namespace App\Filament\Resources\RolesResource\Pages;

use App\Filament\Resources\RolesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

use App\Models\Permission;
use App\Models\Role;

use Illuminate\Database\Eloquent\Model;

class CreateRoles extends CreateRecord
{
    protected static string $resource = RolesResource::class;

    // public Collection $permissions;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Role created successfully';
    }

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     // $this->permissions = collect($data)->filter(function ($permission, $key) {
    //     //     return ! in_array($key, ['role_name', 'role_value', 'select_all']) && Str::contains($key, '_');
    //     // })->keys();
    //     // return Arr::only($data, ['role_name', 'role_value']);
    //     dd($data);
    // }

    // protected function afterCreate(): void
    // {
    //     // dd($this->record); // inserted role data

    //     $permissionModels = collect();
    //     $this->permissions->each(function ($permission) use ($permissionModels){
    //         $permissionModels->push(Permission::firstOrCreate(
    //             ['permission_name' => $permission ]
    //         ));
    //     });

    //     // dd($permissionModels);

    //     // $this->record->syncPermissions($permissionModels);
    //     $this->record->permissions()->sync($permissionModels);

    // }

    // protected function handleRecordCreation(array $data): Model
    // {
    //     dd($data);
    // }
}
