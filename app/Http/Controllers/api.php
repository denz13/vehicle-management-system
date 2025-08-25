<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * ========================================
     * USER MANAGEMENT APIs
     * ========================================
     */

    /**
     * Get all users with pagination
     */
    public function getUsers(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $users = User::with(['department', 'position'])
                ->paginate($perPage);

            return $this->apiResponse($users, 'Users retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving users: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error retrieving users', 500);
        }
    }

    /**
     * Get user by ID
     */
    public function getUserById($id): JsonResponse
    {
        try {
            $user = User::with(['department', 'position'])->find($id);
            
            if (!$user) {
                return $this->apiResponse(null, 'User not found', 404);
            }

            return $this->apiResponse($user, 'User retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving user: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error retrieving user', 500);
        }
    }

    /**
     * Create new user
     */
    public function createUser(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'department_id' => 'required|exists:tbl_department,id',
                'position_id' => 'required|exists:tbl_position,id',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'contact_number' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
            }

            $userData = $request->all();
            $userData['password'] = bcrypt($request->password);
            
            $user = User::create($userData);
            $user->load(['department', 'position']);

            return $this->apiResponse($user, 'User created successfully', 201);
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error creating user', 500);
        }
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, $id): JsonResponse
    {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return $this->apiResponse(null, 'User not found', 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $id,
                'department_id' => 'sometimes|exists:tbl_department,id',
                'position_id' => 'sometimes|exists:tbl_position,id',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'contact_number' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
            }

            $user->update($request->all());
            $user->load(['department', 'position']);

            return $this->apiResponse($user, 'User updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error updating user', 500);
        }
    }

    /**
     * Delete user
     */
    public function deleteUser($id): JsonResponse
    {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return $this->apiResponse(null, 'User not found', 404);
            }

            $user->delete();

            return $this->apiResponse(null, 'User deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error deleting user', 500);
        }
    }

    /**
     * ========================================
     * VEHICLE MANAGEMENT APIs
     * ========================================
     */

    /**
     * Get all vehicles
     */
    public function getVehicles(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $vehicles = tbl_vehicle::paginate($perPage);

            return $this->apiResponse($vehicles, 'Vehicles retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving vehicles: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error retrieving vehicles', 500);
        }
    }

    /**
     * Get vehicle by ID
     */
    public function getVehicleById($id): JsonResponse
    {
        try {
            $vehicle = tbl_vehicle::find($id);
            
            if (!$vehicle) {
                return $this->apiResponse(null, 'Vehicle not found', 404);
            }

            return $this->apiResponse($vehicle, 'Vehicle retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving vehicle: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error retrieving vehicle', 500);
        }
    }

    /**
     * Create new vehicle
     */
    public function createVehicle(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'vehicle_name' => 'required|string|max:255',
                'vehicle_color' => 'required|string|max:100',
                'model' => 'required|string|max:100',
                'plate_number' => 'required|string|max:20|unique:tbl_vehicle,plate_number',
                'capacity' => 'required|integer|min:1',
                'date_acquired' => 'required|date',
                'vehicle_image' => 'nullable|string',
                'status' => 'required|in:available,maintenance,unavailable'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
            }

            $vehicle = tbl_vehicle::create($request->all());

            return $this->apiResponse($vehicle, 'Vehicle created successfully', 201);
        } catch (\Exception $e) {
            Log::error('Error creating vehicle: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error creating vehicle', 500);
        }
    }

    /**
     * Update vehicle
     */
    public function updateVehicle(Request $request, $id): JsonResponse
    {
        try {
            $vehicle = tbl_vehicle::find($id);
            
            if (!$vehicle) {
                return $this->apiResponse(null, 'Vehicle not found', 404);
            }

            $validator = Validator::make($request->all(), [
                'vehicle_name' => 'sometimes|string|max:255',
                'vehicle_color' => 'sometimes|string|max:100',
                'model' => 'sometimes|string|max:100',
                'plate_number' => 'sometimes|string|max:20|unique:tbl_vehicle,plate_number,' . $id,
                'capacity' => 'sometimes|integer|min:1',
                'date_acquired' => 'sometimes|date',
                'vehicle_image' => 'nullable|string',
                'status' => 'sometimes|in:available,maintenance,unavailable'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
            }

            $vehicle->update($request->all());

            return $this->apiResponse($vehicle, 'Vehicle updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating vehicle: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error updating vehicle', 500);
        }
    }

    /**
     * Delete vehicle
     */
    public function deleteVehicle($id): JsonResponse
    {
        try {
            $vehicle = tbl_vehicle::find($id);
            
            if (!$vehicle) {
                return $this->apiResponse(null, 'Vehicle not found', 404);
            }

            $vehicle->delete();

            return $this->apiResponse(null, 'Vehicle deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting vehicle: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error deleting vehicle', 500);
        }
    }

    /**
     * ========================================
     * VEHICLE RESERVATION APIs
     * ========================================
     */

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

    /**
     * Get vehicle reservation by ID
     */
    public function getVehicleReservationById($id): JsonResponse
    {
        try {
            $reservation = tbl_reserve_vehicle::with(['vehicle', 'user', 'reservation_type', 'passengers', 'driver'])
                ->find($id);
            
            if (!$reservation) {
                return $this->apiResponse(null, 'Vehicle reservation not found', 404);
            }

            return $this->apiResponse($reservation, 'Vehicle reservation retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving vehicle reservation: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error retrieving vehicle reservation', 500);
        }
    }

    /**
     * Create new vehicle reservation
     */
    public function createVehicleReservation(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'vehicle_id' => 'required|exists:tbl_vehicle,id',
                'user_id' => 'required|exists:users,id',
                'requested_name' => 'required|string|max:255',
                'destination' => 'required|string',
                'longitude' => 'nullable|numeric',
                'latitude' => 'nullable|numeric',
                'driver' => 'nullable|string|max:255',
                'driver_user_id' => 'nullable|exists:users,id',
                'start_datetime' => 'required|date|after:now',
                'end_datetime' => 'required|date|after:start_datetime',
                'reason' => 'required|string',
                'remarks' => 'nullable|string',
                'reservation_type_id' => 'required|exists:tbl_reservation_type,id',
                'status' => 'in:pending,approved,rejected,completed,cancelled'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
            }

            $reservation = tbl_reserve_vehicle::create($request->all());
            $reservation->load(['vehicle', 'user', 'reservation_type', 'passengers', 'driver']);

            return $this->apiResponse($reservation, 'Vehicle reservation created successfully', 201);
        } catch (\Exception $e) {
            Log::error('Error creating vehicle reservation: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error creating vehicle reservation', 500);
        }
    }

    /**
     * Update vehicle reservation
     */
    public function updateVehicleReservation(Request $request, $id): JsonResponse
    {
        try {
            $reservation = tbl_reserve_vehicle::find($id);
            
            if (!$reservation) {
                return $this->apiResponse(null, 'Vehicle reservation not found', 404);
            }

            $validator = Validator::make($request->all(), [
                'vehicle_id' => 'sometimes|exists:tbl_vehicle,id',
                'user_id' => 'sometimes|exists:users,id',
                'requested_name' => 'sometimes|string|max:255',
                'destination' => 'sometimes|string',
                'longitude' => 'nullable|numeric',
                'latitude' => 'nullable|numeric',
                'driver' => 'nullable|string|max:255',
                'driver_user_id' => 'nullable|exists:users,id',
                'start_datetime' => 'sometimes|date',
                'end_datetime' => 'sometimes|date|after:start_datetime',
                'reason' => 'sometimes|string',
                'remarks' => 'nullable|string',
                'reservation_type_id' => 'sometimes|exists:tbl_reservation_type,id',
                'status' => 'sometimes|in:pending,approved,rejected,completed,cancelled'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
            }

            $reservation->update($request->all());
            $reservation->load(['vehicle', 'user', 'reservation_type', 'passengers', 'driver']);

            return $this->apiResponse($reservation, 'Vehicle reservation updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating vehicle reservation: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error updating vehicle reservation', 500);
        }
    }

    /**
     * Delete vehicle reservation
     */
    public function deleteVehicleReservation($id): JsonResponse
    {
        try {
            $reservation = tbl_reserve_vehicle::find($id);
            
            if (!$reservation) {
                return $this->apiResponse(null, 'Vehicle reservation not found', 404);
            }

            $reservation->delete();

            return $this->apiResponse(null, 'Vehicle reservation deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting vehicle reservation: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error deleting vehicle reservation', 500);
        }
    }

    /**
     * ========================================
     * DEPARTMENT MANAGEMENT APIs
     * ========================================
     */

    /**
     * Get all departments
     */
    public function getDepartments(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $departments = tbl_department::paginate($perPage);

            return $this->apiResponse($departments, 'Departments retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving departments: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error retrieving departments', 500);
        }
    }

    /**
     * Create new department
     */
    public function createDepartment(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'department_name' => 'required|string|max:255|unique:tbl_department,department_name',
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
            }

            $department = tbl_department::create($request->all());

            return $this->apiResponse($department, 'Department created successfully', 201);
        } catch (\Exception $e) {
            Log::error('Error creating department: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error creating department', 500);
        }
    }

    /**
     * Update department
     */
    public function updateDepartment(Request $request, $id): JsonResponse
    {
        try {
            $department = tbl_department::find($id);
            
            if (!$department) {
                return $this->apiResponse(null, 'Department not found', 404);
            }

            $validator = Validator::make($request->all(), [
                'department_name' => 'sometimes|string|max:255|unique:tbl_department,department_name,' . $id,
                'description' => 'nullable|string',
                'status' => 'sometimes|in:active,inactive'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
            }

            $department->update($request->all());

            return $this->apiResponse($department, 'Department updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating department: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error updating department', 500);
        }
    }

    /**
     * ========================================
     * POSITION MANAGEMENT APIs
     * ========================================
     */

    /**
     * Get all positions
     */
    public function getPositions(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $positions = tbl_position::paginate($perPage);

            return $this->apiResponse($positions, 'Positions retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving positions: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error retrieving positions', 500);
        }
    }

    /**
     * Create new position
     */
    public function createPosition(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'position_name' => 'required|string|max:255|unique:tbl_position,position_name',
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
            }

            $position = tbl_position::create($request->all());

            return $this->apiResponse($position, 'Position created successfully', 201);
        } catch (\Exception $e) {
            Log::error('Error creating position: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error creating position', 500);
        }
    }

    /**
     * ========================================
     * POST/ANNOUNCEMENT APIs
     * ========================================
     */

    /**
     * Get all posts/announcements
     */
    public function getPosts(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $posts = tbl_post::paginate($perPage);

            return $this->apiResponse($posts, 'Posts retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving posts: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error retrieving posts', 500);
        }
    }

    /**
     * Create new post/announcement
     */
    public function createPost(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'announcement_title' => 'required|string|max:255',
                'description' => 'required|string',
                'status' => 'required|in:active,inactive'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
            }

            $post = tbl_post::create($request->all());

            return $this->apiResponse($post, 'Post created successfully', 201);
        } catch (\Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error creating post', 500);
        }
    }

    /**
     * ========================================
     * RESERVATION TYPE APIs
     * ========================================
     */

    /**
     * Get all reservation types
     */
    public function getReservationTypes(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $types = tbl_reservation_type::paginate($perPage);

            return $this->apiResponse($types, 'Reservation types retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving reservation types: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error retrieving reservation types', 500);
        }
    }

    /**
     * ========================================
     * PASSENGER MANAGEMENT APIs
     * ========================================
     */

    /**
     * Get passengers for a reservation
     */
    public function getPassengers($reservationId): JsonResponse
    {
        try {
            $passengers = tbl_reserve_vehicle_passenger::with(['passenger', 'reserve_vehicle'])
                ->where('reserve_vehicle_id', $reservationId)
                ->get();

            return $this->apiResponse($passengers, 'Passengers retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving passengers: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error retrieving passengers', 500);
        }
    }

    /**
     * Add passenger to reservation
     */
    public function addPassenger(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'reserve_vehicle_id' => 'required|exists:tbl_reserve_vehicle,id',
                'passenger_id' => 'required|exists:users,id',
                'passenger_name' => 'required|string|max:255',
                'status' => 'in:confirmed,pending,cancelled'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
            }

            $passenger = tbl_reserve_vehicle_passenger::create($request->all());
            $passenger->load(['passenger', 'reserve_vehicle']);

            return $this->apiResponse($passenger, 'Passenger added successfully', 201);
        } catch (\Exception $e) {
            Log::error('Error adding passenger: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error adding passenger', 500);
        }
    }

    /**
     * ========================================
     * QR CODE SCANNING APIs
     * ========================================
     */

    /**
     * Get scan records for a reservation
     */
    public function getScanRecords($reservationId): JsonResponse
    {
        try {
            $scans = tbl_scan_qrcode_reservation::with(['reservation'])
                ->where('reserve_vehicle_id', $reservationId)
                ->orderBy('logtime', 'desc')
                ->get();

            return $this->apiResponse($scans, 'Scan records retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving scan records: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error retrieving scan records', 500);
        }
    }

    /**
     * Record QR code scan
     */
    public function recordScan(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'reserve_vehicle_id' => 'required|exists:tbl_reserve_vehicle,id',
                'workstate' => 'required|string|max:100',
                'logtime' => 'required|date'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
            }

            $scan = tbl_scan_qrcode_reservation::create($request->all());
            $scan->load(['reservation']);

            return $this->apiResponse($scan, 'Scan recorded successfully', 201);
        } catch (\Exception $e) {
            Log::error('Error recording scan: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error recording scan', 500);
        }
    }

    /**
     * ========================================
     * CHAT MESSAGES APIs
     * ========================================
     */

    /**
     * Get chat messages between users
     */
    public function getChatMessages(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'from_user_id' => 'required|exists:users,id',
                'to_user_id' => 'required|exists:users,id'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
            }

            $messages = tbl_chat_messages::with(['sender', 'receiver'])
                ->where(function($query) use ($request) {
                    $query->where('from_user_id', $request->from_user_id)
                          ->where('to_user_id', $request->to_user_id);
                })
                ->orWhere(function($query) use ($request) {
                    $query->where('from_user_id', $request->to_user_id)
                          ->where('to_user_id', $request->from_user_id);
                })
                ->orderBy('created_at', 'asc')
                ->get();

            return $this->apiResponse($messages, 'Chat messages retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving chat messages: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error retrieving chat messages', 500);
        }
    }

    /**
     * Send chat message
     */
    public function sendMessage(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'from_user_id' => 'required|exists:users,id',
                'to_user_id' => 'required|exists:users,id',
                'message' => 'required|string',
                'status' => 'in:sent,delivered,read'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, 'Validation failed', 422, $validator->errors());
            }

            $chatMessage = tbl_chat_messages::create($request->all());
            $chatMessage->load(['sender', 'receiver']);

            return $this->apiResponse($chatMessage, 'Message sent successfully', 201);
        } catch (\Exception $e) {
            Log::error('Error sending message: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error sending message', 500);
        }
    }

    /**
     * ========================================
     * DASHBOARD & ANALYTICS APIs
     * ========================================
     */

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): JsonResponse
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'total_vehicles' => tbl_vehicle::count(),
                'active_reservations' => tbl_reserve_vehicle::where('status', 'approved')->count(),
                'pending_reservations' => tbl_reserve_vehicle::where('status', 'pending')->count(),
                'total_departments' => tbl_department::count(),
                'total_positions' => tbl_position::count(),
                'active_posts' => tbl_post::where('status', 'active')->count(),
                'total_reservation_types' => tbl_reservation_type::count()
            ];

            return $this->apiResponse($stats, 'Dashboard statistics retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving dashboard stats: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error retrieving dashboard statistics', 500);
        }
    }

    /**
     * Get vehicle availability status
     */
    public function getVehicleAvailability(): JsonResponse
    {
        try {
            $vehicles = tbl_vehicle::select('id', 'vehicle_name', 'plate_number', 'status', 'capacity')
                ->get();

            return $this->apiResponse($vehicles, 'Vehicle availability retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving vehicle availability: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error retrieving vehicle availability', 500);
        }
    }

    /**
     * Search functionality across models
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q');
            $type = $request->get('type', 'all');

            if (!$query) {
                return $this->apiResponse(null, 'Search query is required', 400);
            }

            $results = [];

            if ($type === 'all' || $type === 'users') {
                $results['users'] = User::where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->with(['department', 'position'])
                    ->limit(10)
                    ->get();
            }

            if ($type === 'all' || $type === 'vehicles') {
                $results['vehicles'] = tbl_vehicle::where('vehicle_name', 'like', "%{$query}%")
                    ->orWhere('plate_number', 'like', "%{$query}%")
                    ->orWhere('model', 'like', "%{$query}%")
                    ->limit(10)
                    ->get();
            }

            if ($type === 'all' || $type === 'reservations') {
                $results['reservations'] = tbl_reserve_vehicle::where('requested_name', 'like', "%{$query}%")
                    ->orWhere('destination', 'like', "%{$query}%")
                    ->with(['vehicle', 'user'])
                    ->limit(10)
                    ->get();
            }

            return $this->apiResponse($results, 'Search completed successfully');
        } catch (\Exception $e) {
            Log::error('Error performing search: ' . $e->getMessage());
            return $this->apiResponse(null, 'Error performing search', 500);
        }
    }
}
