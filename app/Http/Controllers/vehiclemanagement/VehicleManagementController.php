<?php

namespace App\Http\Controllers\vehiclemanagement;

use App\Http\Controllers\Controller;
use App\Models\tbl_vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class VehicleManagementController extends Controller
{
    public function index()
    {
        $vehicles = tbl_vehicle::paginate(10);
        return view('vehicle-management.vehicle-management', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_name' => 'required|string|max:255',
            'vehicle_color' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'plate_number' => 'required|string|max:50|unique:tbl_vehicle,plate_number',
            'capacity' => 'required|integer|min:1',
            'date_acquired' => 'required|date',
            'vehicle_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            
            // Handle image upload
            if ($request->hasFile('vehicle_image')) {
                $image = $request->file('vehicle_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/vehicles', $imageName);
                $data['vehicle_image'] = $imageName;
            }
            
            $vehicle = tbl_vehicle::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle created successfully',
                'vehicle' => $vehicle
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create vehicle',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getVehicles(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        
        $vehicles = tbl_vehicle::paginate($perPage, ['*'], 'page', $page);
        
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

    public function edit($id)
    {
        try {
            $vehicle = tbl_vehicle::findOrFail($id);
            return response()->json([
                'success' => true,
                'vehicle' => $vehicle
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_name' => 'required|string|max:255',
            'vehicle_color' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'plate_number' => 'required|string|max:50|unique:tbl_vehicle,plate_number,' . $id,
            'capacity' => 'required|integer|min:1',
            'date_acquired' => 'required|date',
            'vehicle_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vehicle = tbl_vehicle::findOrFail($id);
            $data = $request->all();
            
            // Handle image upload
            if ($request->hasFile('vehicle_image')) {
                // Delete old image if exists
                if ($vehicle->vehicle_image && Storage::exists('public/vehicles/' . $vehicle->vehicle_image)) {
                    Storage::delete('public/vehicles/' . $vehicle->vehicle_image);
                }
                
                $image = $request->file('vehicle_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/vehicles', $imageName);
                $data['vehicle_image'] = $imageName;
            }
            
            $vehicle->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle updated successfully',
                'vehicle' => $vehicle
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vehicle',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $vehicle = tbl_vehicle::findOrFail($id);
            
            // Delete image if exists
            if ($vehicle->vehicle_image && Storage::exists('public/vehicles/' . $vehicle->vehicle_image)) {
                Storage::delete('public/vehicles/' . $vehicle->vehicle_image);
            }
            
            $vehicle->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vehicle deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vehicle',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
