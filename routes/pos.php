<?php

use App\Http\Controllers\API\AccessoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\OrderAPIController;
use App\Http\Controllers\API\EntityAPIController;
use App\Http\Controllers\API\InvoiceAPIController;
use App\Http\Controllers\API\NotificationController;

Route::middleware('auth:api')->group(function () {
    Route::controller(EntityAPIController::class)->group(function () {
        Route::get('/areas/{id}/entities', 'getEntityWithInvoice');
        Route::get('/entities_sessions/{id}', 'getEntitySessionDetail');
        Route::get('/entities/{id}', 'entitySessionWithInvoiceDetail');
        Route::get('/table/{id}', 'tableWithInvoiceDetail');
        Route::get('/areas/{id}/inactive_entities','getOnlyInactiveEntities');
    });

    Route::controller(InvoiceAPIController::class)->group(function () {
        Route::post('entities/add_service', 'addService');
        Route::post('entities/end_service', 'endService');
        Route::get('/invoices', 'getInvoiceData');
        Route::post('/entities/start', 'startEntity');
        Route::post('/entities/add_more_sessions', 'addMoreSessions');
        Route::post('/entities/change', 'changeRoom');
        Route::post('/entities/done', 'endRoom');
        Route::post('/room_done', 'doneRoom');
        Route::post('/entities/confirm','roomConfirm');
    });
    Route::controller(NotificationController::class)->group(function () {
        Route::post('pos/send_notification', 'sendPosNotification');
    });
    Route::controller(OrderAPIController::class)->group(function () {
        Route::post('/entities/orders', 'addOrder');
        Route::post("/order_status_change", 'orderItemChangeStatus');
        Route::get('/order_items', 'getOrderItemList');
        Route::get('/order_items/{invoiceId}/invoice', 'getOrderItemByInvoice');
        Route::get('/pos_order_items', 'getOrderItemForPOS');
        Route::post('/pos_order_items/{order_item_id}/status', 'orderItemAreaConfirm');
        Route::post('/pos_orders/check_foc_supervision', 'checkFocSupervision');
    });

    //ksk
    Route::prefix('pos')->controller(AccessoryController::class)->group(function () {
        Route::get('accessory_by_category/{accessory_category}', 'getAccessoryByCategory');
        Route::get('/get_accessory_category', 'getAccessoryCategory');
        Route::post('/add_accessory', 'createInvoiceAccessory');
    });
});