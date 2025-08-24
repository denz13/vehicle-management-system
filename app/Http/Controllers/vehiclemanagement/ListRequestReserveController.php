<?php

namespace App\Http\Controllers\vehiclemanagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\tbl_reserve_vehicle;
use App\Models\tbl_reservation_type;
use App\Models\tbl_reserve_vehicle_passenger;
use App\Models\tbl_vehicle;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ListRequestReserveController extends Controller
{
    public function index()
    {
        // Get all reservation types
        $reservationTypes = tbl_reservation_type::where('status', 'active')->get();
        
        // Get all vehicle reservations with related data
        $reservations = tbl_reserve_vehicle::with([
            'vehicle',
            'user',
            'reservation_type',
            'passengers.passenger'
        ])->orderBy('created_at', 'desc')->get();
        
        // Get all vehicles
        $vehicles = tbl_vehicle::all();
        
        // Get all users
        $users = User::all();
        
        // Get all passengers
        $passengers = tbl_reserve_vehicle_passenger::with(['passenger', 'reserve_vehicle'])->get();
        
        return view('vehicle-management.list-request-reserve', compact(
            'reservationTypes',
            'reservations',
            'vehicles',
            'users',
            'passengers'
        ));
    }

    public function show($id)
    {
        try {
            // Debug: Log the ID being received
            Log::info('Show method called with ID: ' . $id);
            
            // Check if the ID is valid
            if (!is_numeric($id) || $id <= 0) {
                Log::error('Invalid ID provided: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid reservation ID'
                ], 400);
            }
            
            // Get basic reservation data first
            $reservation = tbl_reserve_vehicle::find($id);
            if (!$reservation) {
                Log::error('Reservation not found with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation not found'
                ], 404);
            }
            
            Log::info('Basic reservation found, ID: ' . $reservation->id);
            
            // Convert to array to avoid any model serialization issues
            $reservationData = $reservation->toArray();
            
            // Add basic relationships manually to avoid issues
            if ($reservation->vehicle) {
                $reservationData['vehicle'] = $reservation->vehicle->toArray();
            }
            
            if ($reservation->user) {
                $reservationData['user'] = $reservation->user->toArray();
            }
            
            if ($reservation->reservation_type) {
                $reservationData['reservation_type'] = $reservation->reservation_type->toArray();
            }
            
            // Get passengers separately
            $passengers = tbl_reserve_vehicle_passenger::where('reserve_vehicle_id', $id)->get();
            $reservationData['passengers'] = $passengers->map(function($passenger) {
                if ($passenger->passenger) {
                    return [
                        'passenger' => $passenger->passenger->toArray()
                    ];
                }
                return null;
            })->filter()->values();
            
            Log::info('Reservation data prepared successfully');

            return response()->json([
                'success' => true,
                'reservation' => $reservationData
            ]);
        } catch (\Exception $e) {
            Log::error('Error in show method: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found or error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
