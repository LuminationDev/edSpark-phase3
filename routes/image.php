<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ImageController;


Route::group([],function(){
    Route::post('imageUpload/{type?}', [ImageController::class, 'imageUpload']);
    Route::post('imageUploadEditorjs', [ImageController::class, 'imageUploadEditorjs']);
});