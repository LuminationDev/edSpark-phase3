<?php

namespace App\Filament\Resources\SoftwareResource\Pages;

use App\Filament\Resources\SoftwareResource;
use App\Models\Software;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CreateSoftware extends CreateRecord
{
    protected static string $resource = SoftwareResource::class;

    private function cleanExtraContent($dataArray)
    {
        foreach ($dataArray as &$data) {
            $extraContent = $data['data']['extra_content'];

            foreach ($extraContent as $key => &$content) {
                if (isset($content['item']) && is_array($content['item'])) {
                    foreach ($content['item'] as $index => $item) {
                        if (is_array($item)) {
                            $allValuesEmpty = true;

                            // Check if all values of the current item are empty or null
                            foreach ($item as $value) {
                                if ($value !== null && $value !== "") {
                                    $allValuesEmpty = false;
                                    break;
                                }
                            }
                            if ($allValuesEmpty) {
                                unset($content['item'][$index]);
                            }
                        } elseif ($item === null || $item === "") {
                            unset($content['item'][$index]);
                        }
                    }

                    // If the 'item' key has become empty, remove it
                    if (empty($content['item'])) {
                        unset($extraContent[$key]);
                    }
                } else {
                    // If the content key has other empty keys, remove them
                    foreach ($content as $contentKey => $contentValue) {
                        if (empty($contentValue)) {
                            unset($extraContent[$key][$contentKey]);
                        }
                    }

                    // If the entire content key has become empty, remove it
                    if (empty($extraContent[$key])) {
                        unset($extraContent[$key]);
                    }
                }
            }

            $data['data']['extra_content'] = $extraContent;
        }

        return $dataArray;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['author_id'] = Auth::user()->id;
        $data['extra_content'] = $this->cleanExtraContent($data['extra_content']);
        $data['post_date'] = Carbon::now();
        $data['post_modified'] = Carbon::now();
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = parent::handleRecordCreation($data);
        $record->save();
        //handle tags
        if (isset($data['tags'])) {
            $thatEvent = Software::find($record->id);
            $thatEvent->attachTags($data['tags']);
        }
        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Software created successfully';
    }
}
