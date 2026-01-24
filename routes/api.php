<?php

use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\DeviceIpController;
use App\Http\Controllers\Api\NetworkController;
use Illuminate\Support\Facades\Route;

Route::prefix('networks')->group(function () {
    Route::get('', [NetworkController::class, 'list'])->name('network.list');
    Route::post('', [NetworkController::class, 'store'])->name('network.store');
    Route::get('{networkId}', [NetworkController::class, 'show'])->name('network.show');
    Route::put('{networkId}', [NetworkController::class, 'update'])->name('network.update');
    Route::delete('{networkId}', [NetworkController::class, 'delete'])->name('network.delete');
})->name('network.list');

Route::prefix('devices')->group(function () {
    Route::get('', [DeviceController::class, 'list'])->name('device.list');
    Route::post('', [DeviceController::class, 'store'])->name('device.store');
    Route::get('{deviceId}', [DeviceController::class, 'show'])->name('device.show');
    Route::put('{deviceId}', [DeviceController::class, 'update'])->name('device.update');
    Route::delete('{deviceId}', [DeviceController::class, 'delete'])->name('device.delete');
    Route::post('{deviceId}/ip', [DeviceIpController::class, 'store'])->name('device.ip.store');
});
