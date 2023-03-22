<?php

namespace App\Filament\Resources\RolesResource\Pages;

use App\Filament\Resources\RolesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;


class ListRoles extends ListRecords
{
    protected static string $resource = RolesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),

            ImportAction::make()
                ->fields([
                    ImportField::make('role_name')
                        ->label('Role Name'),
                    ImportField::make('role_value')
                        ->label('Role Value')
                ])
        ];
    }
}
