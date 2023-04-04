<?php

namespace App\Filament\Resources\AdviceResource\Pages;

use App\Filament\Resources\AdviceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\Advicemeta;

class CreateAdvice extends CreateRecord
{
    protected static string $resource = AdviceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
//         dd($data);
        $data['author_id'] = Auth::user()->id;
        $data['post_date'] = Carbon::now();
        $data['post_modified'] = Carbon::now();
        // dd($data);
        return $data;
    }

    // protected function handleRecordCreation(array $data): Model
    // {
    //     // dd($data);
    //     //if no temp content
    //     // $dataToInsert = [
    //     //     "author_id" => $data['author_id'],
    //     //     "post_title" => $data['post_title'],
    //     //     "post_content" => $data['post_content'],
    //     //     "post_excerpt" => $data['post_excerpt'],
    //     //     "cover_image" => $data['cover_image'],
    //     //     "post_date" => $data['post_date'],
    //     //     "post_modified" => $data['post_modified'],
    //     //     "post_status" => $data['post_status'],
    //     //     "advicetype_id" => $data['advice_type']
    //     // ];

    //     // $lastInsertId = static::getModel()::insertGetId($data);

    //     // if temp content
    //     if (isset($data['template']) && !empty($data['temp_content'])){
    //         // dd('template exists');
    //         $dataToInsertIntoMetaTable = [];

    //         foreach ($data['temp_content']['numbered_items']['item'] as $key => $value) {
    //             // dd($value);
    //             // dd($value);
    //             $result = [
    //                 // 'advice_id' => $lastInsertId,
    //                 'advice_id' => 1,
    //                 'advice_meta_key' => 'extra_content',
    //                 'advice_meta_value' > implode(',', $value),
    //                 'created_at' => Carbon::now(),
    //                 'updated_at' => Carbon::now()
    //             ];
    //             $dataToInsertIntoMetaTable[] = $result;
    //         }
    //         dd($dataToInsertIntoMetaTable);
    //         // Advicemeta::insert($dataToInsertIntoMetaTable);
    //     }

    //     // return true;
    // }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Advice created successfully';
    }
}
