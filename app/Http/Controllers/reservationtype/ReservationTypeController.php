<?php

namespace App\Http\Controllers\reservationtype;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\tbl_reservation_type;
use Illuminate\Support\Facades\Log;

class ReservationTypeController extends Controller
{
    public function index()
    {
        $reservationTypes = tbl_reservation_type::orderBy('created_at', 'desc')->paginate(10);
        return view('reservation-type.reservation-type', compact('reservationTypes'));
    }

    public function getReservationTypes()
    {
        $reservationTypes = tbl_reservation_type::orderBy('created_at', 'desc')->paginate(10);
        return response()->json([
            'success' => true,
            'reservationTypes' => $reservationTypes->items(),
            'pagination' => [
                'current_page' => $reservationTypes->currentPage(),
                'last_page' => $reservationTypes->lastPage(),
                'per_page' => $reservationTypes->perPage(),
                'total' => $reservationTypes->total(),
                'from' => $reservationTypes->firstItem(),
                'to' => $reservationTypes->lastItem()
            ]
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'reservation_name' => 'required|string|max:255|unique:tbl_reservation_type,reservation_name',
                'description' => 'nullable|string|max:500',
                'status' => 'required|in:active,inactive'
            ]);

            $reservationType = tbl_reservation_type::create([
                'reservation_name' => $request->reservation_name,
                'description' => $request->description,
                'status' => $request->status
            ]);

            Log::info('Reservation type created successfully', ['id' => $reservationType->id]);

            return response()->json([
                'success' => true,
                'message' => 'Reservation type created successfully',
                'reservationType' => $reservationType
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating reservation type: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create reservation type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $reservationType = tbl_reservation_type::find($id);
            
            if (!$reservationType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation type not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'reservationType' => $reservationType
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching reservation type for edit: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reservation type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'reservation_name' => 'required|string|max:255|unique:tbl_reservation_type,reservation_name,' . $id,
                'description' => 'nullable|string|max:500',
                'status' => 'required|in:active,inactive'
            ]);

            $reservationType = tbl_reservation_type::find($id);
            
            if (!$reservationType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation type not found'
                ], 404);
            }

            $reservationType->update([
                'reservation_name' => $request->reservation_name,
                'description' => $request->description,
                'status' => $request->status
            ]);

            Log::info('Reservation type updated successfully', ['id' => $reservationType->id]);

            return response()->json([
                'success' => true,
                'message' => 'Reservation type updated successfully',
                'reservationType' => $reservationType
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating reservation type: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update reservation type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $reservationType = tbl_reservation_type::find($id);
            
            if (!$reservationType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation type not found'
                ], 404);
            }

            // Check if this reservation type is being used in any reservations
            $usageCount = \App\Models\tbl_reserve_vehicle::where('reservation_type_id', $id)->count();
            
            if ($usageCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete reservation type. It is being used by ' . $usageCount . ' reservation(s).'
                ], 422);
            }

            $reservationType->delete();

            Log::info('Reservation type deleted successfully', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Reservation type deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting reservation type: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete reservation type: ' . $e->getMessage()
            ], 500);
        }
    }
}
