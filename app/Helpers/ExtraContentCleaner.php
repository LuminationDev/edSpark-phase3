<?php

namespace App\Helpers;

class ExtraContentCleaner
{
    public static function cleanExtraContent($dataArray)
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
}