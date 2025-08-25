<?php

namespace App\Http\Controllers\livetracking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\tbl_reserve_vehicle;
use App\Models\tbl_reserve_vehicle_passenger;
use Illuminate\Support\Facades\Auth;

class LiveTrackingController extends Controller
{
    public function index()
    {
        // Get the currently logged-in user
        $currentUser = Auth::user();
        
        // Debug: Check all reservations first
        $allReservations = tbl_reserve_vehicle::with(['vehicle', 'user', 'reservation_type', 'passengers.passenger'])->get();
        
        // Get approved reservations where the current user is the driver
        $approvedReservations = tbl_reserve_vehicle::with(['vehicle', 'user', 'reservation_type', 'passengers.passenger'])
            ->where('driver_user_id', $currentUser->id)
            ->where('status', 'approved')
            ->orderBy('start_datetime', 'asc')
            ->get();
            
        // Debug: Log the data being passed to the view
        \Log::info('Live Tracking Data:', [
            'user_id' => $currentUser->id,
            'user_name' => $currentUser->name,
            'total_reservations_in_system' => $allReservations->count(),
            'approved_reservations_for_driver' => $approvedReservations->count(),
            'all_reservations_sample' => $allReservations->take(3)->map(function($res) {
                return [
                    'id' => $res->id,
                    'driver_user_id' => $res->driver_user_id,
                    'status' => $res->status,
                    'requested_name' => $res->requested_name,
                    'destination' => $res->destination
                ];
            }),
            'driver_reservations_sample' => $approvedReservations->take(3)->map(function($res) {
                return [
                    'id' => $res->id,
                    'driver_user_id' => $res->driver_user_id,
                    'status' => $res->status,
                    'requested_name' => $res->requested_name,
                    'destination' => $res->destination
                ];
            })
        ]);
            
        return view('live-tracking.live-tracking', compact('approvedReservations', 'currentUser', 'allReservations'));
    }
    
    public function getReservations()
    {
        try {
            $currentUser = Auth::user();
            
            $reservations = tbl_reserve_vehicle::with(['vehicle', 'user', 'reservation_type', 'passengers.passenger'])
                ->where('driver_user_id', $currentUser->id)
                ->where('status', 'approved')
                ->orderBy('start_datetime', 'asc')
                ->get();
                
            return response()->json([
                'success' => true,
                'reservations' => $reservations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reservations',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
