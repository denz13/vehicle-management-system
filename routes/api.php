<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ========================================
// VEHICLE MANAGEMENT SYSTEM API ROUTES
// ========================================

// Public routes (no authentication required)
Route::prefix('v1')->group(function () {
    
    // ========================================
    // USER MANAGEMENT ROUTES
    // ========================================
    Route::prefix('users')->group(function () {
        Route::get('/', [ApiController::class, 'getUsers']);
        Route::get('/{id}', [ApiController::class, 'getUserById']);
        Route::post('/', [ApiController::class, 'createUser']);
        Route::put('/{id}', [ApiController::class, 'updateUser']);
        Route::delete('/{id}', [ApiController::class, 'deleteUser']);
    });

    // ========================================
    // VEHICLE MANAGEMENT ROUTES
    // ========================================
    Route::prefix('vehicles')->group(function () {
        Route::get('/', [ApiController::class, 'getVehicles']);
        Route::get('/{id}', [ApiController::class, 'getVehicleById']);
        Route::post('/', [ApiController::class, 'createVehicle']);
        Route::put('/{id}', [ApiController::class, 'updateVehicle']);
        Route::delete('/{id}', [ApiController::class, 'deleteVehicle']);
        Route::get('/availability/status', [ApiController::class, 'getVehicleAvailability']);
    });

    // ========================================
    // VEHICLE RESERVATION ROUTES
    // ========================================
    Route::prefix('reservations')->group(function () {
        Route::get('/', [ApiController::class, 'getVehicleReservations']);
        Route::get('/{id}', [ApiController::class, 'getVehicleReservationById']);
        Route::post('/', [ApiController::class, 'createVehicleReservation']);
        Route::put('/{id}', [ApiController::class, 'updateVehicleReservation']);
        Route::delete('/{id}', [ApiController::class, 'deleteVehicleReservation']);
    });

    // ========================================
    // DEPARTMENT MANAGEMENT ROUTES
    // ========================================
    Route::prefix('departments')->group(function () {
        Route::get('/', [ApiController::class, 'getDepartments']);
        Route::post('/', [ApiController::class, 'createDepartment']);
        Route::put('/{id}', [ApiController::class, 'updateDepartment']);
    });

    // ========================================
    // POSITION MANAGEMENT ROUTES
    // ========================================
    Route::prefix('positions')->group(function () {
        Route::get('/', [ApiController::class, 'getPositions']);
        Route::post('/', [ApiController::class, 'createPosition']);
    });

    // ========================================
    // POST/ANNOUNCEMENT ROUTES
    // ========================================
    Route::prefix('posts')->group(function () {
        Route::get('/', [ApiController::class, 'getPosts']);
        Route::post('/', [ApiController::class, 'createPost']);
    });

    // ========================================
    // RESERVATION TYPE ROUTES
    // ========================================
    Route::prefix('reservation-types')->group(function () {
        Route::get('/', [ApiController::class, 'getReservationTypes']);
    });

    // ========================================
    // PASSENGER MANAGEMENT ROUTES
    // ========================================
    Route::prefix('passengers')->group(function () {
        Route::get('/reservation/{reservationId}', [ApiController::class, 'getPassengers']);
        Route::post('/', [ApiController::class, 'addPassenger']);
    });

    // ========================================
    // QR CODE SCANNING ROUTES
    // ========================================
    Route::prefix('scans')->group(function () {
        Route::get('/reservation/{reservationId}', [ApiController::class, 'getScanRecords']);
        Route::post('/', [ApiController::class, 'recordScan']);
    });

    // ========================================
    // CHAT MESSAGES ROUTES
    // ========================================
    Route::prefix('chat')->group(function () {
        Route::get('/messages', [ApiController::class, 'getChatMessages']);
        Route::post('/send', [ApiController::class, 'sendMessage']);
    });

    // ========================================
    // DASHBOARD & ANALYTICS ROUTES
    // ========================================
    Route::prefix('dashboard')->group(function () {
        Route::get('/stats', [ApiController::class, 'getDashboardStats']);
    });

    // ========================================
    // SEARCH FUNCTIONALITY ROUTES
    // ========================================
    Route::get('/search', [ApiController::class, 'search']);

    // ========================================
    // HEALTH CHECK ROUTE
    // ========================================
    Route::get('/health', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'Vehicle Management System API is running',
            'timestamp' => now(),
            'version' => '1.0.0'
        ]);
    });
});

// ========================================
// API DOCUMENTATION ROUTE
// ========================================
Route::get('/docs', function () {
    return response()->json([
        'message' => 'Vehicle Management System API Documentation',
        'version' => '1.0.0',
        'endpoints' => [
            'users' => [
                'GET /api/v1/users' => 'Get all users',
                'GET /api/v1/users/{id}' => 'Get user by ID',
                'POST /api/v1/users' => 'Create new user',
                'PUT /api/v1/users/{id}' => 'Update user',
                'DELETE /api/v1/users/{id}' => 'Delete user'
            ],
            'vehicles' => [
                'GET /api/v1/vehicles' => 'Get all vehicles',
                'GET /api/v1/vehicles/{id}' => 'Get vehicle by ID',
                'POST /api/v1/vehicles' => 'Create new vehicle',
                'PUT /api/v1/vehicles/{id}' => 'Update vehicle',
                'DELETE /api/v1/vehicles/{id}' => 'Delete vehicle',
                'GET /api/v1/vehicles/availability/status' => 'Get vehicle availability status'
            ],
            'reservations' => [
                'GET /api/v1/reservations' => 'Get all vehicle reservations',
                'GET /api/v1/reservations/{id}' => 'Get reservation by ID',
                'POST /api/v1/reservations' => 'Create new reservation',
                'PUT /api/v1/reservations/{id}' => 'Update reservation',
                'DELETE /api/v1/reservations/{id}' => 'Delete reservation'
            ],
            'departments' => [
                'GET /api/v1/departments' => 'Get all departments',
                'POST /api/v1/departments' => 'Create new department',
                'PUT /api/v1/departments/{id}' => 'Update department'
            ],
            'positions' => [
                'GET /api/v1/positions' => 'Get all positions',
                'POST /api/v1/positions' => 'Create new position'
            ],
            'posts' => [
                'GET /api/v1/posts' => 'Get all posts/announcements',
                'POST /api/v1/posts' => 'Create new post'
            ],
            'reservation-types' => [
                'GET /api/v1/reservation-types' => 'Get all reservation types'
            ],
            'passengers' => [
                'GET /api/v1/passengers/reservation/{id}' => 'Get passengers for reservation',
                'POST /api/v1/passengers' => 'Add passenger to reservation'
            ],
            'scans' => [
                'GET /api/v1/scans/reservation/{id}' => 'Get scan records for reservation',
                'POST /api/v1/scans' => 'Record QR code scan'
            ],
            'chat' => [
                'GET /api/v1/chat/messages' => 'Get chat messages between users',
                'POST /api/v1/chat/send' => 'Send chat message'
            ],
            'dashboard' => [
                'GET /api/v1/dashboard/stats' => 'Get dashboard statistics'
            ],
            'search' => [
                'GET /api/v1/search' => 'Search across all models'
            ],
            'health' => [
                'GET /api/v1/health' => 'API health check'
            ]
        ]
    ]);
});
