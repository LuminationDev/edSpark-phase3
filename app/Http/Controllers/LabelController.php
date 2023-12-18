<?php
namespace App\Http\Controllers;

use App\Models\Label;
use App\Services\ResponseService;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function fetchAllLabels(){
        $labels = Label::get();
        $result = [];
        foreach ($labels as $label){
            $result[] = [
                'label_id'  => $label->id,
                'label_type' => $label->type,
                'label_value' => $label->value,
                'label_description' => $label->description
            ];

        }
        return ResponseService::success('Success', $result);
    }

}