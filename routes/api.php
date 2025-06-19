<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RobotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('robot')->group(function() {
    // Send command (from frontend or dashboard)
    Route::post('/command', [RobotController::class, 'storeCommand'])->name('robot.command.store');
    
    // Get pending command (for ESP32 to poll)
    Route::get('/command/{apiKey}', [RobotController::class, 'getPendingCommand']);
    
    // Update command status (from ESP32)
    Route::post('/command-status/{apiKey}', [RobotController::class, 'updateCommandStatusByKey']);

    // Get sensor logs reading data (from ESP32)
    Route::post('/robot/sensor-data', [RobotController::class, 'storeSensorData']);
});


Route::get('/search-users', [ProfileController::class, 'search'])->name('api.users.search');
Route::get('/all-users', [ProfileController::class, 'getAllUsers'])->name('api.users.all');