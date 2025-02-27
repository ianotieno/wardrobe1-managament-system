<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClothingItemController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);





Route::middleware('auth:sanctum')->group(function () {
    Route::get('/clothing-items', [ClothingItemController::class, 'index']);
    Route::post('/clothing-items', [ClothingItemController::class, 'store']);
    Route::post('/clothing-items/{id}', [ClothingItemController::class, 'update']); // Use POST
    Route::delete('/clothing-items/{id}', [ClothingItemController::class, 'destroy']);
    Route::post('/logout', [ClothingItemController::class, 'logout']);
});