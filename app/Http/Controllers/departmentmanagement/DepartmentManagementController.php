<?php

namespace App\Http\Controllers\departmentmanagement;

use App\Http\Controllers\Controller;
use App\Models\tbl_department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentManagementController extends Controller
{
    public function index()
    {
        $departments = tbl_department::paginate(10);
        return view('department-management.department-management', compact('departments'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_name' => 'required|string|max:255|unique:tbl_department,department_name',
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
            $department = tbl_department::create([
                'department_name' => $request->department_name,
                'description' => $request->description,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Department created successfully',
                'department' => $department
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create department',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getDepartments(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        
        $departments = tbl_department::paginate($perPage, ['*'], 'page', $page);
        
        return response()->json([
            'success' => true,
            'departments' => $departments->items(),
            'pagination' => [
                'current_page' => $departments->currentPage(),
                'last_page' => $departments->lastPage(),
                'per_page' => $departments->perPage(),
                'total' => $departments->total(),
                'from' => $departments->firstItem(),
                'to' => $departments->lastItem()
            ]
        ]);
    }

    public function edit($id)
    {
        try {
            $department = tbl_department::findOrFail($id);
            return response()->json([
                'success' => true,
                'department' => $department
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'department_name' => 'required|string|max:255|unique:tbl_department,department_name,' . $id,
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
            $department = tbl_department::findOrFail($id);
            $department->update([
                'department_name' => $request->department_name,
                'description' => $request->description,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Department updated successfully',
                'department' => $department
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update department',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $department = tbl_department::findOrFail($id);
            $department->delete();

            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete department',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
