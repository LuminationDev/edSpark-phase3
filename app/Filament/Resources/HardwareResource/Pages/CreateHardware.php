<?php

namespace App\Filament\Resources\HardwareResource\Pages;

use App\Filament\Resources\HardwareResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CreateHardware extends CreateRecord
{
    protected static string $resource = HardwareResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data); // Before mutation
        $data['product_owner'] = Auth::user()->id;
        $data['created'] = Carbon::now();
        $data['modified'] = Carbon::now();
        $data['product_isLoan'] = false;
        $data['product_SKU'] = '100';
        // dd($data); // After mutation

        return $data;

    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Hardware created successfully';
    }
}
