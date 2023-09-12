<?php

namespace App\Helpers;

use Carbon\Carbon;


class Metahelper
{
    public static function insert(int $id, $metaData, string $idColumn ,string $keyColumn, string $valueColumn, $model)
    {
        if(isset($metaData)) {
            try {
                foreach ($metaData as $key => $value) {
                    $result = [
                        "$idColumn" => $id,
                        "$keyColumn" => $key,
                        "$valueColumn" => (is_string($value)) ? $value : implode(', ', $value),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                    $metadataToInsert[] = $result;
                }

                $model::insert($metadataToInsert);
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
    }

    public static function update()
    {
        return 'update';
    }

    public static function getMeta($metaModel, $mainModel, string $idColumn ,string $keyColumn, string $valueColumn){
        $metadata = $metaModel::where($idColumn, $mainModel->id)->get();
        return $metadata->map(function ($item) use ($keyColumn , $valueColumn) {
            return [
                "$keyColumn" => $item->{$keyColumn},
                "$valueColumn" => $item->{$valueColumn}
            ];
        })->toArray();
    }

    public static function checkHasMetakey($metaArray, $key, $keyColumn): bool
    {
        if(count($metaArray) == 0) return false;
        foreach ($metaArray as $item) {
            if ($item["$keyColumn"] == $key) {
                return true;
            }
        }

        return false;
    }



}
