<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CustomerController;
use App\Http\Controllers\Web\LeadController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// Swagger API Documentation (Publicly accessible for API consumers & evaluators)
Route::get('docs', function () {
    return view('docs.swagger');
})->name('docs');

Route::get('api/documentation', function () {
    return view('docs.swagger');
})->name('api.documentation');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('leads.index');
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('switch-role/{role}', [AuthController::class, 'switchRole'])->name('switch-role');

    // Leads Module
    Route::resource('leads', LeadController::class)->except(['show']);
    Route::post('leads/{lead}/convert', [LeadController::class, 'convert'])->name('leads.convert');

    // Customers Module (Listing page only)
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
});
