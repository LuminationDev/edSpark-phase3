<?php

namespace App\Filament\Resources\PartnerprofileResource\Pages;

use App\Filament\Resources\PartnerprofileResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use Illuminate\Support\Facades\View;

use Carbon\Carbon;
use Filament\Peek\Preview;

class EditPartnerprofile extends EditRecord
{
    use HasPreviewModal;

    protected static string $resource = PartnerprofileResource::class;

    protected function getActions(): array
    {
        return [
//            Actions\DeleteAction::make(),
            PreviewAction::make(),
        ];
    }

    //using laravel blade
    protected function getPreviewModalView(): ?string
    {
        return 'pages.preview';
    }

    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'partner';
    }


    // using vue js
//    protected function renderPreviewModalView($view, $data): string
//    {
//        dd('aa');
//        $data = [
//            'title' => 'Preview Title',
//            'description' => "Preview Description",
//        ];
//
//        return View::make('preview-component', $data)->render();
//    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_at'] = Carbon::now();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Partner profile updated successfully';
    }
}
