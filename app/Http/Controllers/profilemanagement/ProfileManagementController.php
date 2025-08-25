<?php

namespace App\Http\Controllers\profilemanagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileManagementController extends Controller
{
    /**
     * Display the profile management page
     */
    public function index()
    {
        return view('profile-management.profile-management');
    }

    /**
     * Update user profile information
     */
    public function update(Request $request)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();
            
            // Get only the fields that are present in the request
            $dataToUpdate = $request->only([
                'name', 'email', 'contact_number', 'address', 'date_of_birth', 'gender'
            ]);
            
            // Keep all fields including empty ones, but filter out null values
            $dataToUpdate = array_filter($dataToUpdate, function($value) {
                return $value !== null;
            });
            
            // If no fields to update, return success
            if (empty($dataToUpdate)) {
                return response()->json([
                    'success' => true,
                    'message' => 'No changes detected'
                ]);
            }
            
            // Update user with only the fields that were sent
            $user->update($dataToUpdate);
            
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => $user->fresh()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
                'new_password_confirmation' => 'required|string',
            ], [
                'current_password.required' => 'Current password is required',
                'new_password.required' => 'New password is required',
                'new_password.min' => 'New password must be at least 8 characters',
                'new_password.confirmed' => 'Password confirmation does not match',
                'new_password_confirmation.required' => 'Password confirmation is required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if current password is correct
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'current_password' => ['Current password is incorrect']
                    ]
                ], 422);
            }

            // Check if new password is different from current password
            if (Hash::check($request->new_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'new_password' => ['New password must be different from current password']
                    ]
                ], 422);
            }

            // Update password
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Password change error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while changing the password. Please try again.'
            ], 500);
        }
    }

    /**
     * Get user profile information
     */
    public function getProfile()
    {
        try {
            $user = Auth::user()->load(['department', 'position']);
            
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            \Log::error('Get profile error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching profile information.'
            ], 500);
        }
    }

    /**
     * Upload profile photo
     */
    public function uploadPhoto(Request $request)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            ], [
                'profile_photo.required' => 'Profile photo is required',
                'profile_photo.image' => 'File must be an image',
                'profile_photo.mimes' => 'Image must be JPEG, PNG, JPG, or GIF',
                'profile_photo.max' => 'Image size must be less than 5MB',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle file upload
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                
                // Delete old photo if exists
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }
                
                // Generate unique filename
                $filename = 'profile_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Store the new photo
                $path = $file->storeAs('profiles', $filename, 'public');
                
                // Update user's photo field
                $user->update([
                    'photo' => $path
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Profile photo uploaded successfully!',
                    'photo_url' => Storage::disk('public')->url($path)
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No photo file provided'
            ], 400);

        } catch (\Exception $e) {
            \Log::error('Profile photo upload error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading the photo. Please try again.'
            ], 500);
        }
    }
}
