<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Collection;

use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\Select;

use App\Models\Role;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // protected function getTableBulkActions():array
    // {
    //     return [
    //         BulkAction::make('Attach Role')
    //         ->action(function (Collection $records, array $data): void {
    //             foreach ($records as $record) {
    //                 $record->roles()->sync($data['role']);
    //                 $record->save();
    //             }
    //         })
    //         ->form([
    //             Select::make('role')
    //                 ->options(Role::query()->pluck('role_name', 'id'))
    //                 ->required(),
    //         ])->deselectRecordsAfterCompletion()
    //     ];
    // }


}
