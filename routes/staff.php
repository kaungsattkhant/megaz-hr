<?php

use App\Http\Controllers\API\BenefitController;
use App\Http\Controllers\API\ContractController;
use App\Http\Controllers\API\RoleAPIController;
use App\Http\Controllers\Staff\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::prefix('benefit_requests')->controller(BenefitController::class)->group(function () {
        Route::post('/', 'createBenefitRequest');
        Route::get('/', 'listBenefitRequest');
        Route::get('/benefit_by_type/{type}', 'getBenefitByType');
    });

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
