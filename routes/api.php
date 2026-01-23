<?php

use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\NetworkController;
use Illuminate\Support\Facades\Route;

Route::prefix('networks')->group(function () {
    Route::get('', [NetworkController::class, 'list']);
    Route::post('', [NetworkController::class, 'store']);
    Route::get('{networkId}', [NetworkController::class, 'show']);
    Route::put('{networkId}', [NetworkController::class, 'update']);
    Route::delete('{networkId}', [NetworkController::class, 'delete']);
});

Route::prefix('devices')->group(function () {
    Route::get('', [DeviceController::class, 'list']);
    Route::post('', [DeviceController::class, 'store']);
    Route::get('{deviceId}', [DeviceController::class, 'show']);
    Route::put('{deviceId}', [DeviceController::class, 'update']);
    Route::delete('{deviceId}', [DeviceController::class, 'delete']);
});
