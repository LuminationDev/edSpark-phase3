<?php

namespace App\Filament\Resources\AdviceResource\Pages;

use App\Filament\Resources\AdviceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;


class EditAdvice extends EditRecord
{
    protected static string $resource = AdviceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDatabeforeFill(array $data): array
    {
        // $data['content'] = $data['extra_content'][static::getTemplateName($data['template'])];
        // unset($data['content']);
        // dd($data);
        $data['Author'] = Auth::user()->full_name;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // $data['content'] = $data['temp_content'][static::getTemplateName($data['template'])];
        // unset($data['temp_content']);

        $data['post_modified'] = Carbon::now();
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
        return 'Advice updated successfully';
    }
}
