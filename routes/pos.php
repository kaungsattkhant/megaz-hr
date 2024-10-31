<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\InvoiceAPIController;

Route::middleware('auth:api')->group(function () {
    //     Route::controller(InvoiceAPIController::class)->group(function () {
//         Route::post('entities/add_service', 'addService');
//         Route::post('entities/end_service', 'endService');
//     });
});