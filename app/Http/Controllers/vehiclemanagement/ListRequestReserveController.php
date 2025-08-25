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
        $reservations = tbl_reserve_vehicle::with(['vehicle', 'user', 'reservation_type', 'passengers.passenger'])
            ->orderBy('created_at', 'desc')
            ->get();

        $vehicles = tbl_vehicle::all();
        $users = User::all();
        $reservationTypes = tbl_reservation_type::all();
        
        // Get unique statuses from the reservations table
        $uniqueStatuses = tbl_reserve_vehicle::select('status')
            ->distinct()
            ->pluck('status')
            ->filter() // Remove null/empty values
            ->values();

        return view('vehicle-management.list-request-reserve', compact(
            'reservations', 
            'vehicles', 
            'users', 
            'reservationTypes',
            'uniqueStatuses'
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

    public function approveReservation($id)
    {
        try {
            Log::info('Approve reservation called with ID: ' . $id);
            
            $reservation = tbl_reserve_vehicle::find($id);
            if (!$reservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation not found'
                ], 404);
            }
            
            // Update the status to approved
            $reservation->status = 'approved';
            $reservation->save();
            
            Log::info('Reservation approved successfully, ID: ' . $id);
            
            return response()->json([
                'success' => true,
                'message' => 'Reservation approved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error approving reservation: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve reservation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function declineReservation($id)
    {
        try {
            Log::info('Decline reservation called with ID: ' . $id);
            
            $reservation = tbl_reserve_vehicle::find($id);
            if (!$reservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation not found'
                ], 404);
            }
            
            // Get remarks from request
            $remarks = request()->input('remarks');
            if (empty($remarks)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Remarks are required to decline a reservation'
                ], 400);
            }
            
            // Update the status to rejected and save remarks
            $reservation->status = 'rejected';
            $reservation->remarks = $remarks;
            $reservation->save();
            
            Log::info('Reservation declined successfully, ID: ' . $id . ', Remarks: ' . $remarks);
            
            return response()->json([
                'success' => true,
                'message' => 'Reservation declined successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error declining reservation: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to decline reservation: ' . $e->getMessage()
            ], 500);
        }
    }
}
