<?php

use App\Http\Controllers\API\ContractController;
use App\Http\Controllers\API\RoleAPIController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\HomeController;

Route::middleware('auth:api')->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('home', 'index');
    });
    Route::controller(RoleAPIController::class)->group(function () {
        Route::get('get_organization_chart', 'getOrganizationChart');
    });
    Route::controller(ContractController::class)->group(function(){
        Route::get('get_contracts','getContractList');
        Route::post('signed_contract','signedContract');
    });
});
