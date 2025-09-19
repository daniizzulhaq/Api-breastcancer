<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\VideoController;

// Test route
Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is working!',
        'timestamp' => now()
    ]);
});

// API Routes
Route::prefix('v1')->group(function () {
    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);
    
    // Materials
    Route::get('/materials', [MaterialController::class, 'index']);
    Route::get('/materials/{slug}', [MaterialController::class, 'show']);
    
    // Videos
    Route::get('/videos', [VideoController::class, 'index']);
    Route::get('/videos/{slug}', [VideoController::class, 'show']);
});

// Fallback untuk routes yang tidak ditemukan
Route::fallback(function() {
    return response()->json([
        'status' => 'error',
        'message' => 'API endpoint not found'
    ], 404);
});