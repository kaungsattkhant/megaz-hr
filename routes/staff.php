<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\HomeController;

Route::middleware('auth:api')->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('home', 'index');
    });
});
