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
                'id'  => $label->id,
                'type' => $label->type,
                'value' => $label->value,
                'description' => $label->description
            ];

        }
        return ResponseService::success('Success', $result);
    }

}