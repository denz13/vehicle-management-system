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
            $reservation = tbl_reserve_vehicle::with(['vehicle', 'reservation_type'])
                ->where('id', $id)
                ->where('user_id', $user->id)
                ->first();
            
            // Load passengers separately to handle soft-deleted records
            if ($reservation) {
                $passengers = tbl_reserve_vehicle_passenger::where('reserve_vehicle_id', $id)
                    ->whereNull('deleted_at') // Only active passengers
                    ->get();
                $reservation->passengers = $passengers;
            }
            
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
                'passengers_data' => $reservation->passengers ? $reservation->passengers->toArray() : [],
                'passengers_relationship_loaded' => $reservation->relationLoaded('passengers')
            ]);
            
            // Debug: Log passenger structure
            if ($reservation->passengers && $reservation->passengers->count() > 0) {
                \Log::info('First passenger structure:', [
                    'first_passenger' => $reservation->passengers->first()->toArray(),
                    'passenger_keys' => array_keys($reservation->passengers->first()->toArray())
                ]);
            }
            
            // Add driver_user_id to the reservation data for frontend
            // If driver field contains a user ID, use it directly
            // If it contains a name, find the user ID
            if (is_numeric($reservation->driver)) {
                $reservation->driver_user_id = $reservation->driver;
            } else {
                // Find user by name (fallback for existing data)
                $driverUser = User::where('name', $reservation->driver)->first();
                $reservation->driver_user_id = $driverUser ? $driverUser->id : null;
            }
            
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
    
    public function show($id)
    {
        try {
            $user = Auth::user();
            
            \Log::info('Show method called for reservation ID: ' . $id . ', User ID: ' . $user->id);
            
            // Find the reservation and ensure it belongs to the current user
            $reservation = tbl_reserve_vehicle::with(['vehicle', 'reservation_type', 'user'])
                ->where('id', $id)
                ->where('user_id', $user->id)
                ->first();
            
            if (!$reservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation not found or you do not have permission to view it.'
                ], 404);
            }
            
            // Load passengers separately to handle soft-deleted records
            \Log::info('Loading passengers for reservation ID: ' . $id);
            $passengers = tbl_reserve_vehicle_passenger::where('reserve_vehicle_id', $id)
                ->whereNull('deleted_at') // Only active passengers
                ->get();
            \Log::info('Found ' . $passengers->count() . ' passengers');
            
            // Format the data for display
            $formattedReservation = [
                'id' => $reservation->id,
                'vehicle' => [
                    'vehicle_name' => $reservation->vehicle->vehicle_name ?? 'N/A',
                    'plate_number' => $reservation->vehicle->plate_number ?? 'N/A',
                    'vehicle_image' => $reservation->vehicle->vehicle_image ?? null,
                ],
                'destination' => $reservation->destination,
                'longitude' => $reservation->longitude,
                'latitude' => $reservation->latitude,
                'driver' => $reservation->driver,
                'start_datetime' => $reservation->start_datetime,
                'end_datetime' => $reservation->end_datetime,
                'reason' => $reservation->reason,
                'reservation_type' => [
                    'reservation_name' => $reservation->reservation_type->reservation_name ?? 'N/A',
                ],
                'status' => $reservation->status,
                'requested_by' => $reservation->user->name ?? 'N/A',
                'qrcode' => $reservation->qrcode,
                'passengers' => $passengers->map(function($passenger) {
                    return [
                        'id' => $passenger->passenger_id,
                        'name' => $passenger->passenger_name ?? 'Unknown Passenger',
                        'status' => $passenger->status ?? 'active'
                    ];
                }),
                'created_at' => $reservation->created_at,
                'updated_at' => $reservation->updated_at
            ];
            
            \Log::info('Successfully formatted reservation data, returning response');
            return response()->json([
                'success' => true,
                'reservation' => $formattedReservation
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in show method: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load reservation details: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            
            // Debug: Log the incoming request data
            \Log::info('Update request data:', $request->all());
            
            // Early debug to see if we reach this point
            \Log::info('REACHED UPDATE METHOD - ID: ' . $id . ', User ID: ' . $user->id);
            
            // Find the reservation and ensure it belongs to the current user
            $reservation = tbl_reserve_vehicle::where('id', $id)
                ->where('user_id', $user->id)
                ->first();
            
            // Debug: Check if reservation was found
            \Log::info('Reservation lookup successful', [
                'reservation_id' => $reservation->id,
                'status' => $reservation->status,
                'user_id' => $reservation->user_id
            ]);
            
            // Debug: Check validation process
            \Log::info('About to validate request data', [
                'request_fields' => array_keys($request->all()),
                'request_data' => $request->all()
            ]);
            
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
            
            // Validate request - make fields optional for partial updates
            try {
                $request->validate([
                    'destination' => 'nullable|string|max:255',
                    'longitude' => 'nullable|string|max:50',
                    'latitude' => 'nullable|string|max:50',
                    'driver' => 'nullable|exists:users,id',
                    'start_datetime' => 'nullable|date',
                    'end_datetime' => 'nullable|date',
                    'reason' => 'nullable|string|max:500',
                    'reservation_type_id' => 'nullable|integer|min:1',
                    'passengers' => 'nullable|array|min:1',
                    'passengers.*' => 'nullable|exists:users,id'
                ]);
                
                // Additional validation for datetime fields if both are provided
                if ($request->filled('start_datetime') && $request->filled('end_datetime')) {
                    $request->validate([
                        'end_datetime' => 'after:start_datetime'
                    ]);
                }
                
                \Log::info('Validation passed successfully');
            } catch (\Illuminate\Validation\ValidationException $e) {
                \Log::error('Validation failed:', [
                    'errors' => $e->errors(),
                    'request_data' => $request->all()
                ]);
                
                // Log validation errors and re-throw
                \Log::error('Validation failed:', [
                    'errors' => $e->errors(),
                    'request_data' => $request->all()
                ]);
                throw $e;
            }
            
            // Prepare update data - only include fields that are actually different
            $updateData = [];
            
            if ($request->filled('destination') && $request->destination !== $reservation->destination) {
                $updateData['destination'] = $request->destination;
            }
            if ($request->filled('longitude') && $request->longitude !== $reservation->longitude) {
                $updateData['longitude'] = $request->longitude;
            }
            if ($request->filled('latitude') && $request->latitude !== $reservation->latitude) {
                $updateData['latitude'] = $request->latitude;
            }
            if ($request->filled('driver') && $request->driver !== $reservation->driver_user_id) {
                $driverUser = User::find($request->driver);
                if (!$driverUser) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected driver not found.'
                    ], 422);
                }
                // Save the driver ID to driver_user_id field and name to driver field
                $updateData['driver_user_id'] = $request->driver; // This is the user ID
                $updateData['driver'] = $driverUser->name; // This is the driver's name
            }
            if ($request->filled('start_datetime') && $request->start_datetime !== $reservation->start_datetime) {
                // Convert ISO format (2025-08-24T18:31) to MySQL format (2025-08-24 18:31:00)
                $startDateTime = \Carbon\Carbon::parse($request->start_datetime)->format('Y-m-d H:i:s');
                $updateData['start_datetime'] = $startDateTime;
            }
            if ($request->filled('end_datetime') && $request->end_datetime !== $reservation->end_datetime) {
                // Convert ISO format (2025-08-24T18:31) to MySQL format (2025-08-24 18:31:00)
                $endDateTime = \Carbon\Carbon::parse($request->end_datetime)->format('Y-m-d H:i:s');
                $updateData['end_datetime'] = $endDateTime;
            }
            if ($request->filled('reason') && $request->reason !== $reservation->reason) {
                $updateData['reason'] = $request->reason;
            }
            if ($request->filled('reservation_type_id') && $request->reservation_type_id != $reservation->reservation_type_id) {
                $updateData['reservation_type_id'] = $request->reservation_type_id;
            }
            
            // Generate new QR code file for the updated reservation
            $qrCodeData = [
                'reservation_id' => $reservation->id,
                'vehicle_id' => $reservation->vehicle_id,
                'user_id' => $reservation->user_id,
                'driver_id' => $request->filled('driver') ? $request->driver : $reservation->driver_user_id,
                'destination' => $request->filled('destination') ? $request->destination : $reservation->destination,
                'longitude' => $request->filled('longitude') ? $request->longitude : $reservation->longitude,
                'latitude' => $request->filled('latitude') ? $request->latitude : $reservation->latitude,
                'start_datetime' => $request->filled('start_datetime') ? $request->start_datetime : $reservation->start_datetime,
                'end_datetime' => $request->filled('end_datetime') ? $request->end_datetime : $reservation->end_datetime,
                'reason' => $request->filled('reason') ? $request->reason : $reservation->reason,
                'reservation_type_id' => $request->filled('reservation_type_id') ? $request->reservation_type_id : $reservation->reservation_type_id,
                'passengers' => $request->has('passengers') ? $request->passengers : [],
                'updated_at' => now()->toISOString()
            ];
            
            // Generate QR code file and get the path
            $qrCodePath = $this->generateQRCode($qrCodeData);
            if ($qrCodePath) {
                $updateData['qrcode'] = $qrCodePath;
            }
            
            // Debug: Show what data will be updated
            \Log::info('Update data to be saved:', $updateData);
            
            // Log update data for debugging
            \Log::info('Update data prepared successfully', [
                'reservation_id' => $reservation->id,
                'update_data_count' => count($updateData),
                'update_fields' => array_keys($updateData),
                'fields_to_update' => $updateData
            ]);
            
            // Log what will be updated
            \Log::info('Smart update - only changing fields:', [
                'fields_to_update' => array_keys($updateData),
                'update_count' => count($updateData)
            ]);
            
            // Update reservation only if there are fields to update
            if (!empty($updateData)) {
                try {
                    // Log what we're about to save
                    \Log::info('About to save update data:', [
                        'fields_to_update' => array_keys($updateData),
                        'update_count' => count($updateData)
                    ]);
                    
                    // Use individual field assignment since guarded: ["*"] prevents fill()
                    foreach ($updateData as $field => $value) {
                        $reservation->$field = $value;
                    }
                    $result = $reservation->save();
                    
                    \Log::info('Update result:', [
                        'success' => $result, 
                        'updated_data' => $updateData,
                        'model_changes' => $reservation->getChanges()
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Update failed:', [
                        'error' => $e->getMessage(), 
                        'trace' => $e->getTraceAsString(),
                        'update_data' => $updateData
                    ]);
                    throw $e;
                }
            }
            
            // Update passengers only if provided
            if ($request->has('passengers') && is_array($request->passengers)) {
                try {
                    // Soft delete existing passengers (mark as deleted)
                    tbl_reserve_vehicle_passenger::where('reserve_vehicle_id', $id)
                        ->update(['deleted_at' => now()]);
                    
                    // Add new passengers (remove duplicates and empty values)
                    $uniquePassengers = array_unique(array_filter($request->passengers, function($passengerId) {
                        return !empty($passengerId);
                    }));
                    
                    if (!empty($uniquePassengers)) {
                        foreach ($uniquePassengers as $passengerId) {
                            // Get user name for the passenger
                            $passengerUser = User::find($passengerId);
                            if ($passengerUser) {
                                tbl_reserve_vehicle_passenger::create([
                                    'reserve_vehicle_id' => $id,
                                    'passenger_id' => $passengerId,
                                    'passenger_name' => $passengerUser->name ?? 'Unknown Passenger',
                                    'status' => 'active'
                                ]);
                            }
                        }
                    }
                    \Log::info('Passengers updated successfully:', ['passengers' => $uniquePassengers]);
                } catch (\Exception $e) {
                    \Log::error('Passenger update failed:', ['error' => $e->getMessage()]);
                    throw $e;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Reservation updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Reservation update failed:', [
                'reservation_id' => $id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update reservation. Please try again.'
            ], 500);
        }
    }
    
    // Generate QR code for reservation (copied from ReserveVehicleController)
    private function generateQRCode($reservationData = null)
    {
        try {
            if ($reservationData) {
                // Create QR code content with reservation details
                $qrContent = json_encode([
                    'type' => 'vehicle_reservation',
                    'reservation_id' => $reservationData['reservation_id'] ?? 'N/A',
                    'vehicle_id' => $reservationData['vehicle_id'] ?? 'N/A',
                    'user_id' => $reservationData['user_id'] ?? 'N/A',
                    'driver_id' => $reservationData['driver_id'] ?? 'N/A',
                    'destination' => $reservationData['destination'] ?? 'N/A',
                    'longitude' => $reservationData['longitude'] ?? 'N/A',
                    'latitude' => $reservationData['latitude'] ?? 'N/A',
                    'start_datetime' => $reservationData['start_datetime'] ?? 'N/A',
                    'end_datetime' => $reservationData['end_datetime'] ?? 'N/A',
                    'reason' => $reservationData['reason'] ?? 'N/A',
                    'reservation_type_id' => $reservationData['reservation_type_id'] ?? 'N/A',
                    'passengers' => $reservationData['passengers'] ?? [],
                    'timestamp' => now()->toISOString()
                ]);
            } else {
                // Fallback content
                $qrContent = json_encode([
                    'type' => 'vehicle_reservation',
                    'timestamp' => now()->toISOString()
                ]);
            }
            
            // Generate unique filename
            $filename = 'qr_' . time() . '_' . uniqid() . '.png';
            
            // Store QR code in storage
            $qrPath = 'qrcodes/' . $filename;
            
            // Generate actual QR code PNG using Endroid library
            if (class_exists('\Endroid\QrCode\QrCode')) {
                try {
                    $qrCode = new \Endroid\QrCode\QrCode($qrContent);
                    
                    // Create writer for PNG format
                    $writer = new \Endroid\QrCode\Writer\PngWriter();
                    
                    // Create result with default settings
                    $result = $writer->write($qrCode);
                    
                    // Get PNG data
                    $pngData = $result->getString();
                    
                    // Save PNG to storage
                    \Storage::disk('public')->put($qrPath, $pngData);
                    
                    \Log::info('QR Code generated successfully using Endroid library');
                } catch (\Exception $e) {
                    \Log::error('Endroid QR Code generation failed: ' . $e->getMessage());
                    // Fallback to text file
                    \Storage::disk('public')->put($qrPath, $qrContent);
                    \Log::info('QR Code generated as text file (Endroid failed)');
                }
            } else {
                // Fallback: create text file if library not available
                \Storage::disk('public')->put($qrPath, $qrContent);
                \Log::info('QR Code generated as text file (Endroid library not available)');
            }
            
            return $qrPath;
            
        } catch (\Exception $e) {
            \Log::error('QR Code generation failed: ' . $e->getMessage());
            return null;
        }
    }
}
