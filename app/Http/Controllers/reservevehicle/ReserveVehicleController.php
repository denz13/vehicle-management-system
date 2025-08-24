<?php

namespace App\Http\Controllers\reservevehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\tbl_vehicle;
use App\Models\tbl_reserve_vehicle;
use App\Models\tbl_reserve_vehicle_passenger;
use App\Models\tbl_reservation_type;
use App\Models\User;

class ReserveVehicleController extends Controller
{
    public function index()
    {
        // Get active vehicles with pagination
        $vehicles = tbl_vehicle::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        // Get reservation types
        $reservationTypes = tbl_reservation_type::where('status', 'active')
            ->orderBy('reservation_name', 'asc')
            ->get();
        
        // Get users for passenger selection
        $users = User::select('id', 'name', 'email')
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
        
        return view('reserve-vehicle.reserve-vehicle', compact('vehicles', 'reservationTypes', 'users'));
    }

    public function getVehicles(Request $request)
    {
        $perPage = $request->get('per_page', 12);
        $page = $request->get('page', 1);
        
        $vehicles = tbl_vehicle::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json([
            'success' => true,
            'vehicles' => $vehicles->items(),
            'pagination' => [
                'current_page' => $vehicles->currentPage(),
                'last_page' => $vehicles->lastPage(),
                'per_page' => $vehicles->perPage(),
                'total' => $vehicles->total(),
                'from' => $vehicles->firstItem(),
                'to' => $vehicles->lastItem()
            ]
        ]);
    }
    
    public function getReservationTypes()
    {
        $reservationTypes = tbl_reservation_type::where('status', 'active')
            ->orderBy('reservation_name', 'asc')
            ->get();
        
        return response()->json([
            'success' => true,
            'reservationTypes' => $reservationTypes
        ]);
    }
    
    public function show($id)
    {
        $vehicle = tbl_vehicle::find($id);
        
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'vehicle' => $vehicle
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:tbl_vehicle,id',
            'requested_user_id' => 'required|exists:users,id',
            'destination' => 'required|string|max:500',
            'longitude' => 'nullable|string|max:50',
            'latitude' => 'nullable|string|max:50',
            'driver' => 'required|exists:users,id',
            'start_datetime' => 'required|date|after:now',
            'end_datetime' => 'required|date|after:start_datetime',
            'reason' => 'required|string|max:1000',
            'reservation_type_id' => 'required|integer|min:1',
            'passengers' => 'required|array|min:1',
            'passengers.*' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if vehicle is available for the requested time
            $conflictingReservation = tbl_reserve_vehicle::where('vehicle_id', $request->vehicle_id)
                ->where('status', '!=', 'cancelled')
                ->where('status', '!=', 'rejected')
                ->where(function($query) use ($request) {
                    $query->whereBetween('start_datetime', [$request->start_datetime, $request->end_datetime])
                          ->orWhereBetween('end_datetime', [$request->start_datetime, $request->end_datetime])
                          ->orWhere(function($q) use ($request) {
                              $q->where('start_datetime', '<=', $request->start_datetime)
                                ->where('end_datetime', '>=', $request->end_datetime);
                          });
                })
                ->first();

            if ($conflictingReservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle is not available for the requested time period. Please select a different time or vehicle.'
                ], 422);
            }

            // Get user names for display
            $requestedUser = User::find($request->requested_user_id);
            $driverUser = User::find($request->driver);

            // Create the reservation
            $reservation = tbl_reserve_vehicle::create([
                'vehicle_id' => $request->vehicle_id,
                'user_id' => $request->requested_user_id,
                'requested_name' => $requestedUser->name ?? 'Unknown User',
                'destination' => $request->destination,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'driver' => $driverUser->name ?? 'Unknown Driver',
                'start_datetime' => $request->start_datetime,
                'end_datetime' => $request->end_datetime,
                'reason' => $request->reason,
                'reservation_type_id' => $request->reservation_type_id,
                'qrcode' => $this->generateQRCode(),
                'status' => 'pending'
            ]);

            // Add passengers
            if ($request->has('passengers') && is_array($request->passengers)) {
                // Log original passengers for debugging
                \Log::info('Original passengers received:', $request->passengers);
                
                // Remove duplicates and filter out empty values
                $uniquePassengers = array_unique(array_filter($request->passengers, function($passengerId) {
                    return !empty($passengerId) && $passengerId !== '';
                }));
                
                // Log unique passengers for debugging
                \Log::info('Unique passengers after deduplication:', $uniquePassengers);
                
                foreach ($uniquePassengers as $passengerId) {
                    $passengerUser = User::find($passengerId);
                    tbl_reserve_vehicle_passenger::create([
                        'reserve_vehicle_id' => $reservation->id,
                        'passenger_id' => $passengerId,
                        'passenger_name' => $passengerUser->name ?? 'Unknown Passenger',
                        'status' => 'active'
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Vehicle reserved successfully! Your reservation has been submitted for approval.',
                'reservation_id' => $reservation->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create reservation. Please try again.'
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $reservation = tbl_reserve_vehicle::with('vehicle')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'reservation' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'requested_name' => 'required|string|max:255',
            'destination' => 'required|string|max:500',
            'longitude' => 'nullable|string|max:50',
            'latitude' => 'nullable|string|max:50',
            'driver' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'reason' => 'required|string|max:1000',
            'reservation_type_id' => 'required|integer|min:1',
            'passenger_count' => 'required|integer|min:1',
            'contact_number' => 'required|string|max:50',
            'status' => 'nullable|string|in:pending,approved,rejected,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $reservation = tbl_reserve_vehicle::findOrFail($id);
            
            $reservation->update([
                'requested_name' => $request->requested_name,
                'destination' => $request->destination,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'driver' => $request->driver,
                'start_datetime' => $request->start_datetime,
                'end_datetime' => $request->end_datetime,
                'reason' => $request->reason,
                'reservation_type_id' => $request->reservation_type_id,
                'status' => $request->status ?? $reservation->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reservation updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update reservation. Please try again.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $reservation = tbl_reserve_vehicle::findOrFail($id);
            
            // Delete associated passengers first
            tbl_reserve_vehicle_passenger::where('reserve_vehicle_id', $id)->delete();
            
            // Delete the reservation
            $reservation->delete();

            return response()->json([
                'success' => true,
                'message' => 'Reservation cancelled successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel reservation. Please try again.'
            ], 500);
        }
    }

    private function generateQRCode()
    {
        // Generate a unique QR code for the reservation
        return 'QR_' . time() . '_' . rand(1000, 9999);
    }
}
