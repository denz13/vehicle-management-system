<?php

namespace App\Http\Controllers\scanqrcode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\tbl_reserve_vehicle;
use App\Models\tbl_scan_qrcode_reservation;
use Illuminate\Support\Facades\DB;

class ScanQrcodeController extends Controller
{
    public function index()
    {
        // Get schedules that have been scanned (either departure or arrival)
        $schedules = tbl_reserve_vehicle::with(['vehicle', 'user'])
            ->where('status', 'approved')
            ->whereHas('scanRecords', function($query) {
                $query->whereIn('workstate', [0, 1]); // 0 = departure, 1 = arrival
            })
            ->orderBy('start_datetime', 'desc')
            ->paginate(12);
            
        return view('scan-qrcode.scan-qrcode', compact('schedules'));
    }

    public function scanQrCode(Request $request)
    {
        try {
            $qrCode = $request->input('qrcode');
            
            if (empty($qrCode)) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code is required'
                ]);
            }

            // Try to parse as JSON first
            $jsonData = json_decode($qrCode, true);
            $reservationId = null;
            
            if ($jsonData && isset($jsonData['reservation_id'])) {
                $reservationId = $jsonData['reservation_id'];
            }

            // Search for the schedule with approved status only
            $schedule = null;
            
            if ($reservationId) {
                // Search by reservation_id from JSON
                $schedule = tbl_reserve_vehicle::with(['vehicle', 'user'])
                    ->where('id', $reservationId)
                    ->where('status', 'approved') // Only approved schedules
                    ->first();
            }
            
            if (!$schedule) {
                // Fallback: search by QR code column
                $schedule = tbl_reserve_vehicle::with(['vehicle', 'user'])
                    ->where('qrcode', $qrCode)
                    ->where('status', 'approved') // Only approved schedules
                    ->first();
            }
            
            if (!$schedule) {
                // Final fallback: search by ID (assuming QR code might just be the ID)
                $schedule = tbl_reserve_vehicle::with(['vehicle', 'user'])
                    ->where('id', $qrCode)
                    ->where('status', 'approved') // Only approved schedules
                    ->first();
            }

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Approved schedule not found. Only approved schedules can be scanned.'
                ]);
            }

            // Get existing scan record
            $scanRecord = tbl_scan_qrcode_reservation::where('reserve_vehicle_id', $schedule->id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Schedule found successfully',
                'schedule' => $schedule,
                'scanRecord' => $scanRecord
            ]);

        } catch (\Exception $e) {
            \Log::error('QR Code scan error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error scanning QR code: ' . $e->getMessage()
            ]);
        }
    }

    public function markDeparture(Request $request)
    {
        try {
            $request->validate([
                'schedule_id' => 'required|integer'
            ]);

            $scheduleId = $request->input('schedule_id');
            
            // Verify the schedule exists and is approved
            $schedule = tbl_reserve_vehicle::where('id', $scheduleId)
                ->where('status', 'approved')
                ->first();
                
            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Approved schedule not found. Only approved schedules can be marked for departure.'
                ]);
            }

            // Create or update scan record
            $scanRecord = tbl_scan_qrcode_reservation::updateOrCreate(
                ['reserve_vehicle_id' => $scheduleId],
                [
                    'workstate' => 0, // 0 = departure
                    'logtime' => now()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Departure marked successfully',
                'scanRecord' => $scanRecord
            ]);

        } catch (\Exception $e) {
            \Log::error('Mark departure error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error marking departure: ' . $e->getMessage()
            ]);
        }
    }

    public function markArrival(Request $request)
    {
        try {
            $request->validate([
                'schedule_id' => 'required|integer'
            ]);

            $scheduleId = $request->input('schedule_id');
            
            // Verify the schedule exists and is approved
            $schedule = tbl_reserve_vehicle::where('id', $scheduleId)
                ->where('status', 'approved')
                ->first();
                
            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Approved schedule not found. Only approved schedules can be marked for arrival.'
                ]);
            }

            // Create or update scan record
            $scanRecord = tbl_scan_qrcode_reservation::updateOrCreate(
                ['reserve_vehicle_id' => $scheduleId],
                [
                    'workstate' => 1, // 1 = arrival
                    'logtime' => now()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Arrival marked successfully',
                'scanRecord' => $scanRecord
            ]);

        } catch (\Exception $e) {
            \Log::error('Mark arrival error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error marking arrival: ' . $e->getMessage()
            ]);
        }
    }

    public function getQrImage($id)
    {
        try {
            // Get the schedule and verify it's approved
            $schedule = tbl_reserve_vehicle::where('id', $id)
                ->where('status', 'approved')
                ->first();
                
            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Approved schedule not found'
                ]);
            }

            if (!$schedule->qrcode) {
                return response()->json([
                    'success' => false,
                    'message' => 'No QR code found for this schedule'
                ]);
            }

            // The qrcode column stores the full path like 'qrcodes/qr_filename.png'
            $qrPath = $schedule->qrcode;
            
            // Check if file exists in storage
            $storagePath = storage_path('app/public/' . $qrPath);
            $publicPath = public_path('storage/' . $qrPath);
            
            if (file_exists($storagePath)) {
                $assetUrl = asset('storage/' . $qrPath);
                return response()->json([
                    'success' => true,
                    'qr_image_url' => $assetUrl,
                    'debug_info' => [
                        'storage_path' => $storagePath,
                        'exists' => file_exists($storagePath),
                        'qr_path' => $qrPath
                    ]
                ]);
            } elseif (file_exists($publicPath)) {
                $assetUrl = asset('storage/' . $qrPath);
                return response()->json([
                    'success' => true,
                    'qr_image_url' => $assetUrl,
                    'debug_info' => [
                        'public_path' => $publicPath,
                        'exists' => file_exists($publicPath),
                        'qr_path' => $qrPath
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code image not found in storage',
                    'debug_info' => [
                        'storage_path' => $storagePath,
                        'public_path' => $publicPath,
                        'qr_path' => $qrPath
                    ]
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Get QR image error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving QR image: ' . $e->getMessage()
            ]);
        }
    }

    // Debug method to see what QR codes are in the system
    public function debugQrCodes()
    {
        try {
            $schedules = tbl_reserve_vehicle::select('id', 'qrcode', 'status', 'start_datetime')
                ->whereNotNull('qrcode')
                ->orderBy('id', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'qr_codes' => $schedules,
                'total_with_qrcode' => tbl_reserve_vehicle::whereNotNull('qrcode')->count(),
                'total_approved' => tbl_reserve_vehicle::where('status', 'approved')->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSchedule($id)
    {
        try {
            $schedule = tbl_reserve_vehicle::with(['vehicle', 'user', 'driver'])
                ->where('id', $id)
                ->first();

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Schedule not found'
                ]);
            }

            return response()->json([
                'success' => true,
                'schedule' => $schedule
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching schedule: ' . $e->getMessage()
            ], 500);
        }
    }
}
