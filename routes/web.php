<?php

use App\Http\Controllers\AlarmController;
use App\Http\Controllers\VehicleInventoryController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\AlarmTypeController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AlarmController::class, 'dashboard']);
Route::post('/SendAlarmData', [AlarmController::class, 'SendAlarmData']);
Route::post('/vehicles', [VehicleInventoryController::class, 'saveVehicle']);
Route::put('/vehicles/{id}', [VehicleInventoryController::class, 'updateVehicle']);
Route::delete('/vehicles/{id}', [VehicleInventoryController::class, 'deleteVehicle']);
Route::get('/vehicles', [VehicleInventoryController::class, 'readAll']);
Route::get('/vehicle/{id}', [VehicleInventoryController::class, 'findVehicleInfo']);
Route::post('/device/create', [DeviceController::class, 'createDeviceAction']);
Route::resource('alarm-types', AlarmTypeController::class);
