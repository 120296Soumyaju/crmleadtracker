<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\LeadController;
use Illuminate\Support\Facades\Route;

// Public API Routes
Route::name('api.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Authenticated API Routes (Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/me', [AuthController::class, 'me'])->name('me');

        // Leads REST API
        Route::apiResource('leads', LeadController::class);

        // Customers REST API (Listing & Show)
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    });
});
