<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;

Route::get('/devices', [DeviceController::class, 'index']);
Route::post('/toggle', [DeviceController::class, 'toggle']); // FIX
Route::post('/devices/toggle-all', [DeviceController::class, 'toggleAll']);
//Route::post('/devices/toggle', [DeviceController::class, 'toggle']); --- IGNORE ---
