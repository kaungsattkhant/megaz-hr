<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\HomeController;

// Route::middleware('auth:api')->group(function () {
    Route::controller(HomeController::class)->group(function(){
        Route::get('home_category_list','getHomeCategoryList');
        Route::get('home_menu_list','getHomeMenuList');
    });
// });
