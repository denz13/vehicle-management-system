<?php

namespace App\Http\Controllers\myreservation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\tbl_reserve_vehicle;
use App\Models\tbl_reserve_vehicle_passenger;
use App\Models\User;
use App\Models\tbl_reservation_type;
use Illuminate\Support\Facades\Auth;

class MyReservationController extends Controller
{
    public function index()
    {
        // Get the currently logged-in user
        $user = Auth::user();
        
        // Fetch reservations for this user
        $reservations = tbl_reserve_vehicle::with(['vehicle', 'reservation_type'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get users for driver and passenger selection
        $users = User::select('id', 'name')->orderBy('name')->get();
        
        // Get reservation types
        $reservationTypes = tbl_reservation_type::where('status', 'active')->orderBy('reservation_name')->get();
        
        return view('my-reservation.my-reservation', compact('reservations', 'user', 'users', 'reservationTypes'));
    }
    
    public function getReservations()
    {
        $user = Auth::user();
        
        $reservations = tbl_reserve_vehicle::with(['vehicle', 'reservation_type'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return response()->json([
            'success' => true,
            'reservations' => $reservations->items(),
            'pagination' => [
                'current_page' => $reservations->currentPage(),
                'last_page' => $reservations->lastPage(),
                'per_page' => $reservations->perPage(),
                'total' => $reservations->total(),
                'from' => $reservations->firstItem(),
                'to' => $reservations->lastItem()
            ]
        ]);
    }
    
    public function cancelReservation($id)
    {
        try {
            $user = Auth::user();
            
            // Find the reservation and ensure it belongs to the current user
            $reservation = tbl_reserve_vehicle::where('id', $id)
                ->where('user_id', $user->id)
                ->first();
            
            if (!$reservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation not found or you do not have permission to cancel it.'
                ], 404);
            }
            
            // Check if reservation can be cancelled (only pending reservations)
            if ($reservation->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending reservations can be cancelled.'
                ], 422);
            }
            
            // Update reservation status to cancelled
            $reservation->update(['status' => 'cancelled']);
            
            return response()->json([
                'success' => true,
                'message' => 'Reservation cancelled successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel reservation. Please try again.'
            ], 500);
        }
    }
    
    public function edit($id)
    {
        try {
            $user = Auth::user();
            
            // Find the reservation and ensure it belongs to the current user
            $reservation = tbl_reserve_vehicle::with(['vehicle', 'reservation_type', 'passengers'])
                ->where('id', $id)
                ->where('user_id', $user->id)
                ->first();
            
            if (!$reservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation not found or you do not have permission to edit it.'
                ], 404);
            }
            
            // Check if reservation can be edited (only pending reservations)
            if ($reservation->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending reservations can be edited.'
                ], 422);
            }
            
            // Debug: Log the reservation data
            \Log::info('Reservation data for edit:', [
                'id' => $reservation->id,
                'destination' => $reservation->destination,
                'driver' => $reservation->driver,
                'passengers_count' => $reservation->passengers ? $reservation->passengers->count() : 0,
                'passengers_data' => $reservation->passengers ? $reservation->passengers->toArray() : []
            ]);
            
            return response()->json([
                'success' => true,
                'reservation' => $reservation
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load reservation data. Please try again.'
            ], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            
            // Find the reservation and ensure it belongs to the current user
            $reservation = tbl_reserve_vehicle::where('id', $id)
                ->where('user_id', $user->id)
                ->first();
            
            if (!$reservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation not found or you do not have permission to update it.'
                ], 422);
            }
            
            // Check if reservation can be updated (only pending reservations)
            if ($reservation->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending reservations can be updated.'
                ], 422);
            }
            
            // Validate request
            $request->validate([
                'destination' => 'required|string|max:255',
                'longitude' => 'nullable|string|max:50',
                'latitude' => 'nullable|string|max:50',
                'driver' => 'required|exists:users,id',
                'start_datetime' => 'required|date|after:now',
                'end_datetime' => 'required|date|after:start_datetime',
                'reason' => 'required|string|max:500',
                'reservation_type_id' => 'required|integer|min:1',
                'passengers' => 'required|array|min:1',
                'passengers.*' => 'required|exists:users,id'
            ]);
            
            // Get driver user name
            $driverUser = User::find($request->driver);
            if (!$driverUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected driver not found.'
                ], 422);
            }
            
            // Update reservation
            $reservation->update([
                'destination' => $request->destination,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'driver' => $driverUser->name,
                'start_datetime' => $request->start_datetime,
                'end_datetime' => $request->end_datetime,
                'reason' => $request->reason,
                'reservation_type_id' => $request->reservation_type_id
            ]);
            
            // Update passengers
            if ($request->has('passengers')) {
                // Remove existing passengers
                tbl_reserve_vehicle_passenger::where('reserve_vehicle_id', $id)->delete();
                
                // Add new passengers (remove duplicates)
                $uniquePassengers = array_unique(array_filter($request->passengers, function($passengerId) {
                    return !empty($passengerId);
                }));
                
                foreach ($uniquePassengers as $passengerId) {
                    tbl_reserve_vehicle_passenger::create([
                        'reserve_vehicle_id' => $id,
                        'user_id' => $passengerId
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Reservation updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update reservation. Please try again.'
            ], 500);
        }
    }
}
