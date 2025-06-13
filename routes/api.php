<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RobotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('robot')->group(function(){
    Route::get('/command/{apiKey}', [RobotController::class, 'getPendingCommand']);
    Route::post('/command-status/{apiKey}', [RobotController::class, 'updateCommandStatusByKey']);
});

Route::get('/search-users', [ProfileController::class, 'search'])->name('api.users.search');
Route::get('/all-users', [ProfileController::class, 'getAllUsers'])->name('api.users.all');
Route::get('/search-global', [SearchController::class, 'globalSearch'])
    ->middleware('auth')
    ->name('api.search.global');