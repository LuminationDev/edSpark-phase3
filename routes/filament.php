<?php

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Route;

//Route::middleware(['web', 'okta', Authenticate::class, Authorize::class])
//    ->group(function () {
//        Filament::mount('resources');
//        // Add other routes as necessary
//    });
