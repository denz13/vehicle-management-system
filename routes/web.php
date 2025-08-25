<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\ProfileManagement\ProfileManagementController;
use App\Http\Controllers\PositionManagement\PositionManagementController;
use App\Http\Controllers\DepartmentManagement\DepartmentManagementController;
use App\Http\Controllers\VehicleManagement\VehicleManagementController;
use App\Http\Controllers\ReserveVehicle\ReserveVehicleController;
use App\Http\Controllers\MyReservation\MyReservationController;
use App\Http\Controllers\vehiclemanagement\ListRequestReserveController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Authentication routes
Route::controller(AuthController::class)->middleware('loggedin')->group(function() {
    Route::get('login', 'loginView')->name('login.index');
    Route::post('login', 'login')->name('login.check');
});

// Protected routes
Route::middleware('auth')->group(function() {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Root route redirects to dashboard
    Route::get('/', function() {
        return redirect()->route('dashboard');
    });

    // Profile Management
    Route::get('profile-management', [ProfileManagementController::class, 'index'])->name('profile-management');

    // Position Management
    Route::get('position-management', [PositionManagementController::class, 'index'])->name('position-management');
    Route::post('position-management', [PositionManagementController::class, 'store'])->name('position-management.store');
    Route::get('position-management/positions', [PositionManagementController::class, 'getPositions'])->name('position-management.positions');
    Route::get('position-management/{id}/edit', [PositionManagementController::class, 'edit'])->name('position-management.edit');
    Route::put('position-management/{id}', [PositionManagementController::class, 'update'])->name('position-management.update');
    Route::delete('position-management/{id}', [PositionManagementController::class, 'destroy'])->name('position-management.destroy');
    
    // Department Management
    Route::get('department-management', [DepartmentManagementController::class, 'index'])->name('department-management');
    Route::post('department-management', [DepartmentManagementController::class, 'store'])->name('department-management.store');
    Route::get('department-management/departments', [DepartmentManagementController::class, 'getDepartments'])->name('department-management.departments');
    Route::get('department-management/{id}/edit', [DepartmentManagementController::class, 'edit'])->name('department-management.edit');
    Route::put('department-management/{id}', [DepartmentManagementController::class, 'update'])->name('department-management.update');
    Route::delete('department-management/{id}', [DepartmentManagementController::class, 'destroy'])->name('department-management.destroy');
    
    // Vehicle Management
    Route::get('vehicle-management', [VehicleManagementController::class, 'index'])->name('vehicle-management');
    Route::post('vehicle-management', [VehicleManagementController::class, 'store'])->name('vehicle-management.store');
    Route::get('vehicle-management/vehicles', [VehicleManagementController::class, 'getVehicles'])->name('vehicle-management.vehicles');
    Route::get('vehicle-management/{id}/edit', [VehicleManagementController::class, 'edit'])->name('vehicle-management.edit');
    Route::put('vehicle-management/{id}', [VehicleManagementController::class, 'update'])->name('vehicle-management.update');
    Route::delete('vehicle-management/{id}', [VehicleManagementController::class, 'destroy'])->name('vehicle-management.destroy');

    // Reserve Vehicle
    Route::get('reserve-vehicle', [ReserveVehicleController::class, 'index'])->name('reserve-vehicle');
    Route::get('reserve-vehicle/vehicles', [ReserveVehicleController::class, 'getVehicles'])->name('reserve-vehicle.vehicles');
    Route::get('reserve-vehicle/reservation-types', [ReserveVehicleController::class, 'getReservationTypes'])->name('reserve-vehicle.reservation-types');
    Route::get('reserve-vehicle/{id}', [ReserveVehicleController::class, 'show'])->name('reserve-vehicle.show');
    Route::post('reserve-vehicle', [ReserveVehicleController::class, 'store'])->name('reserve-vehicle.store');
    Route::get('reserve-vehicle/{id}/edit', [ReserveVehicleController::class, 'edit'])->name('reserve-vehicle.edit');
    Route::put('reserve-vehicle/{id}', [ReserveVehicleController::class, 'update'])->name('reserve-vehicle.update');
    Route::delete('reserve-vehicle/{id}', [ReserveVehicleController::class, 'destroy'])->name('reserve-vehicle.destroy');

    // Route to fetch users for passenger selection
    Route::get('users', function() {
        try {
            $users = \App\Models\User::select('id', 'name', 'email')
                ->whereNotNull('name')
                ->orWhereNotNull('email')
                ->get()
                ->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name ?: $user->email ?: "User {$user->id}",
                        'email' => $user->email
                    ];
                });
            
            return response()->json([
                'success' => true,
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load users: ' . $e->getMessage()
            ], 500);
        }
    })->name('users.index');

    // My Reservation
    Route::get('my-reservation', [MyReservationController::class, 'index'])->name('my-reservation');
    Route::get('my-reservation/reservations', [MyReservationController::class, 'getReservations'])->name('my-reservation.reservations');
    Route::get('my-reservation/data', [MyReservationController::class, 'getReservations'])->name('my-reservation.data');
    Route::get('my-reservation/{id}', [MyReservationController::class, 'show'])->name('my-reservation.show');
    Route::post('my-reservation', [MyReservationController::class, 'store'])->name('my-reservation.store');
    Route::get('my-reservation/{id}/edit', [MyReservationController::class, 'edit'])->name('my-reservation.edit');
    Route::post('my-reservation/{id}', [MyReservationController::class, 'update'])->name('my-reservation.update');
    Route::delete('my-reservation/{id}', [MyReservationController::class, 'destroy'])->name('my-reservation.destroy');
    Route::post('my-reservation/{id}/cancel', [MyReservationController::class, 'cancelReservation'])->name('my-reservation.cancel');

    // list request reserve
    Route::get('list-request-reserve', [ListRequestReserveController::class, 'index'])->name('list-request-reserve');
    Route::get('list-request-reserve/reservations', [ListRequestReserveController::class, 'getReservations'])->name('list-request-reserve.reservations');
    Route::get('list-request-reserve/data', [ListRequestReserveController::class, 'getReservations'])->name('list-request-reserve.data');
    Route::get('list-request-reserve/{id}', [ListRequestReserveController::class, 'show'])->name('list-request-reserve.show');
    Route::post('list-request-reserve', [ListRequestReserveController::class, 'store'])->name('list-request-reserve.store');
    Route::get('list-request-reserve/{id}/edit', [ListRequestReserveController::class, 'edit'])->name('list-request-reserve.edit');
    Route::put('list-request-reserve/{id}', [ListRequestReserveController::class, 'update'])->name('list-request-reserve.update');
    Route::delete('list-request-reserve/{id}', [ListRequestReserveController::class, 'destroy'])->name('list-request-reserve.destroy');
    
    // Approve and decline reservations
    Route::post('vehicle-management/approve-reservation/{id}', [ListRequestReserveController::class, 'approveReservation'])->name('list-request-reserve.approve');
    Route::post('vehicle-management/decline-reservation/{id}', [ListRequestReserveController::class, 'declineReservation'])->name('list-request-reserve.decline');
});

