<?php

use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\DeviceIpController;
use App\Http\Controllers\Api\DeviceNetworkAccessController;
use App\Http\Controllers\Api\NetworkController;
use Illuminate\Support\Facades\Route;

Route::prefix('networks')->name('network.')->group(function () {
    Route::get('', [NetworkController::class, 'list'])->name('list');
    Route::post('', [NetworkController::class, 'store'])->name('store');
    Route::get('{networkId}', [NetworkController::class, 'show'])->name('show');
    Route::put('{networkId}', [NetworkController::class, 'update'])->name('update');
    Route::delete('{networkId}', [NetworkController::class, 'delete'])->name('delete');
});

Route::prefix('devices')->name('device.')->group(function () {
    Route::get('', [DeviceController::class, 'list'])->name('list');
    Route::post('', [DeviceController::class, 'store'])->name('store');
    Route::get('{deviceId}', [DeviceController::class, 'show'])->name('show');
    Route::put('{deviceId}', [DeviceController::class, 'update'])->name('update');
    Route::delete('{deviceId}', [DeviceController::class, 'delete'])->name('delete');
    Route::post('{deviceId}/ip', [DeviceIpController::class, 'store'])->name('ip.store');
});

Route::prefix('device-network-access')->name('device.network.access.')->group(function () {
    Route::post('{access}/refresh-metadata', [DeviceNetworkAccessController::class, 'refreshMetadata'])->name('refresh.metadata');
});
