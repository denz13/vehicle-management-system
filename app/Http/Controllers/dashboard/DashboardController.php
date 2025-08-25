<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\tbl_vehicle;
use App\Models\tbl_post;
use App\Models\tbl_reserve_vehicle;
use App\Models\tbl_scan_qrcode_reservation;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        
        return view('dashboard.dashboard', compact('stats'));
    }
    
    private function getDashboardStats()
    {
        try {
            // Total users
            $totalUsers = User::count();
            
            // Total vehicles
            $totalVehicles = tbl_vehicle::count();
            
            // Total posts
            $totalPosts = tbl_post::count();
            
            // Total approved reservations
            $totalApprovedReservations = tbl_reserve_vehicle::where('status', 'approved')->count();
            
            // Total declined reservations
            $totalDeclinedReservations = tbl_reserve_vehicle::where('status', 'declined')->count();
            
            // Total QR code scans
            $totalQrScans = tbl_scan_qrcode_reservation::count();
            
            // Calculate percentage changes (you can implement this based on your needs)
            $userChange = 0; // Placeholder for percentage change
            $vehicleChange = 0;
            $postChange = 0;
            $reservationChange = 0;
            
            return [
                'users' => [
                    'total' => $totalUsers,
                    'change' => $userChange,
                    'change_type' => $userChange >= 0 ? 'increase' : 'decrease'
                ],
                'vehicles' => [
                    'total' => $totalVehicles,
                    'change' => $vehicleChange,
                    'change_type' => $vehicleChange >= 0 ? 'increase' : 'decrease'
                ],
                'posts' => [
                    'total' => $totalPosts,
                    'change' => $postChange,
                    'change_type' => $postChange >= 0 ? 'increase' : 'decrease'
                ],
                'approved_reservations' => [
                    'total' => $totalApprovedReservations,
                    'change' => 0,
                    'change_type' => 'increase'
                ],
                'declined_reservations' => [
                    'total' => $totalDeclinedReservations,
                    'change' => 0,
                    'change_type' => 'decrease'
                ],
                'qr_scans' => [
                    'total' => $totalQrScans,
                    'change' => 0,
                    'change_type' => 'increase'
                ]
            ];
        } catch (\Exception $e) {
            // Return default values if there's an error
            return [
                'users' => ['total' => 0, 'change' => 0, 'change_type' => 'increase'],
                'vehicles' => ['total' => 0, 'change' => 0, 'change_type' => 'increase'],
                'posts' => ['total' => 0, 'change' => 0, 'change_type' => 'increase'],
                'approved_reservations' => ['total' => 0, 'change' => 0, 'change_type' => 'increase'],
                'declined_reservations' => ['total' => 0, 'change' => 0, 'change_type' => 'decrease'],
                'qr_scans' => ['total' => 0, 'change' => 0, 'change_type' => 'increase']
            ];
        }
    }
}
