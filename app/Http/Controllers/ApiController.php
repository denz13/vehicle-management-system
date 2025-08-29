<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

// Import all models
use App\Models\User;
use App\Models\tbl_vehicle;
use App\Models\tbl_reserve_vehicle;
use App\Models\tbl_department;
use App\Models\tbl_position;
use App\Models\tbl_post;
use App\Models\tbl_reservation_type;
use App\Models\tbl_reserve_vehicle_passenger;
use App\Models\tbl_scan_qrcode_reservation;
use App\Models\tbl_chat_messages;

class ApiController extends Controller
{
    /**
     * API Response Helper
     */
    private function apiResponse($data = null, $message = 'Success', $status = 200, $errors = null): JsonResponse
    {
        $response = [
            'success' => $status >= 200 && $status < 300,
            'message' => $message,
            'data' => $data,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    /**
     * Test connection endpoint
     */
    public function testConnection(): JsonResponse
    {
        return $this->apiResponse(['message' => 'API is working!'], 'Connection successful');
    }

    /**
     * User login
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
            }

            // Find user by email
            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                return $this->apiResponse(null, 'Invalid credentials', 401);
            }

            // Check password
            if (!Hash::check($request->password, $user->password)) {
                return $this->apiResponse(null, 'Invalid credentials', 401);
            }

            // For now, return user without token (we'll add Sanctum later)
            $response = [
                'user' => $user->load(['department', 'position']),
                'token' => 'dummy-token-' . time() // Temporary token
            ];

            return $this->apiResponse($response, 'Login successful');
        } catch (\Exception $e) {
            Log::error('Error during login: ' . $e->getMessage());
            return $this->apiResponse(null, 'Login failed', 500);
        }
    }

    /**
     * Get all vehicle reservations
     */
    public function getVehicleReservations(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $reservations = tbl_reserve_vehicle::with(['vehicle', 'user', 'reservation_type', 'passengers', 'driver'])
                ->paginate($perPage);

            return $this->apiResponse($reservations, 'Vehicle reservations retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving vehicle reservations: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error retrieving vehicle reservations', 500);
        }
    }

    public function updateVehicleReservation(Request $request, int $id): JsonResponse
{
    try {
        $validator = Validator::make($request->all(), [
            'status'  => 'required|string|in:done,completed,cancelled,ongoing,pending',
            'remarks' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
        }

        $reservation = tbl_reserve_vehicle::with(['vehicle','user','reservation_type','passengers','driver'])
            ->find($id);
        if (!$reservation) {
            return $this->apiResponse(null, 'Reservation not found', 404);
        }

        $reservation->status = $request->input('status', 'done');
        if ($request->filled('remarks')) {
            $reservation->remarks = $request->input('remarks');
        }
        // Optionally stamp completion time
        if ($reservation->status === 'done') {
            $reservation->end_datetime = now();
        }
        $reservation->save();

        // Return refreshed with relations
        $reservation->load(['vehicle','user','reservation_type','passengers','driver']);
        return $this->apiResponse($reservation, 'Reservation updated');
    } catch (\Exception $e) {
        \Log::error('updateVehicleReservation error: '.$e->getMessage());
        return $this->apiResponse(null, 'Update failed', 500);
    }
}

    
}