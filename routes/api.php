<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Hash;

// Health check endpoint
Route::get('/health', [ApiController::class, 'testConnection']);

Route::prefix('v1')->group(function () {
    // User management routes
    Route::prefix('users')->group(function () {
        Route::post('/login', [ApiController::class, 'login']);
        Route::get('/', [ApiController::class, 'getUsers']);
        Route::get('/{id}', [ApiController::class, 'getUserById']);
        Route::post('/', [ApiController::class, 'createUser']);
        Route::put('/{id}', [ApiController::class, 'updateUser']);
        Route::delete('/{id}', [ApiController::class, 'deleteUser']);
    });

    // Vehicle management routes
    Route::prefix('vehicles')->group(function () {
        Route::get('/', [ApiController::class, 'getVehicles']);
        Route::get('/{id}', [ApiController::class, 'getVehicleById']);
        Route::post('/', [ApiController::class, 'createVehicle']);
        Route::put('/{id}', [ApiController::class, 'updateVehicle']);
        Route::delete('/{id}', [ApiController::class, 'deleteVehicle']);
    });

    // Vehicle reservation routes
    Route::prefix('reservations')->group(function () {
        Route::get('/', [ApiController::class, 'getVehicleReservations']);
        Route::get('/{id}', [ApiController::class, 'getVehicleReservationById']);
        Route::post('/', [ApiController::class, 'createVehicleReservation']);
        Route::put('/{id}', [ApiController::class, 'updateVehicleReservation']);
        Route::delete('/{id}', [ApiController::class, 'deleteVehicleReservation']);
    });

    // Department routes
    Route::prefix('departments')->group(function () {
        Route::get('/', [ApiController::class, 'getDepartments']);
        Route::post('/', [ApiController::class, 'createDepartment']);
        Route::put('/{id}', [ApiController::class, 'updateDepartment']);
    });

    // Position routes
    Route::prefix('positions')->group(function () {
        Route::get('/', [ApiController::class, 'getPositions']);
        Route::post('/', [ApiController::class, 'createPosition']);
    });

    // Post routes
    Route::prefix('posts')->group(function () {
        Route::get('/', [ApiController::class, 'getPosts']);
        Route::post('/', [ApiController::class, 'createPost']);
    });

    // Reservation type routes
    Route::prefix('reservation-types')->group(function () {
        Route::get('/', [ApiController::class, 'getReservationTypes']);
    });

    // Dashboard routes
    Route::prefix('dashboard')->group(function () {
        Route::get('/stats', [ApiController::class, 'getDashboardStats']);
    });

    // Vehicle availability routes
    Route::prefix('vehicles')->group(function () {
        Route::get('/availability/status', [ApiController::class, 'getVehicleAvailability']);
    });

    // Search route
    Route::get('/search', [ApiController::class, 'search']);
});