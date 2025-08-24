<?php

namespace App\Http\Controllers\positionmanagement;

use App\Http\Controllers\Controller;
use App\Models\tbl_position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PositionManagementController extends Controller
{
    public function index()
    {
        $positions = tbl_position::all();
        return view('position-management.position-management', compact('positions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'position_name' => 'required|string|max:255|unique:tbl_position,position_name',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $position = tbl_position::create([
                'position_name' => $request->position_name,
                'description' => $request->description,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Position created successfully',
                'position' => $position
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create position',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPositions()
    {
        $positions = tbl_position::all();
        return response()->json([
            'success' => true,
            'positions' => $positions
        ]);
    }

    public function destroy($id)
    {
        try {
            $position = tbl_position::findOrFail($id);
            $position->delete();

            return response()->json([
                'success' => true,
                'message' => 'Position deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete position',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $position = tbl_position::findOrFail($id);
            return response()->json([
                'success' => true,
                'position' => $position
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Position not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'position_name' => 'required|string|max:255|unique:tbl_position,position_name,' . $id,
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $position = tbl_position::findOrFail($id);
            $position->update([
                'position_name' => $request->position_name,
                'description' => $request->description,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Position updated successfully',
                'position' => $position
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update position',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
