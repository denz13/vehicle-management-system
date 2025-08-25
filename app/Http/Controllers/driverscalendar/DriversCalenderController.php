<?php

namespace App\Http\Controllers\driverscalendar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\tbl_reserve_vehicle;
use App\Models\User;
use App\Models\tbl_vehicle;

class DriversCalenderController extends Controller
{
    public function index()
    {
        // Fetch all approved driver schedules with related data
        $driverSchedules = tbl_reserve_vehicle::with(['vehicle', 'user'])
            ->where('status', 'approved')
            ->where(function($query) {
                $query->whereNotNull('driver_user_id')
                      ->orWhereNotNull('driver');
            })
            ->orderBy('start_datetime', 'asc')
            ->get();

        // Format the data for the calendar
        $calendarEvents = $driverSchedules->map(function ($schedule) {
            // Handle driver name - could be from driver field (string) or driver relationship (User object)
            $driverName = 'Unknown Driver';
            $driverId = null;
            
            if ($schedule->driver_user_id && $schedule->driver_user_id !== '') {
                // If driver_user_id exists, try to get user name
                $driverUser = User::find($schedule->driver_user_id);
                if ($driverUser) {
                    $driverName = $driverUser->name;
                    $driverId = $schedule->driver_user_id;
                }
            } elseif ($schedule->driver && $schedule->driver !== '') {
                // If driver field has a string value, use it directly
                $driverName = $schedule->driver;
                $driverId = null;
            }
            
            return [
                'id' => $schedule->id,
                'title' => $driverName,
                'start' => $schedule->start_datetime,
                'end' => $schedule->end_datetime,
                'driver_name' => $driverName,
                'vehicle_name' => $schedule->vehicle ? $schedule->vehicle->vehicle_name : 'Unknown Vehicle',
                'requester_name' => $schedule->user ? $schedule->user->name : 'Unknown User',
                'destination' => $schedule->destination,
                'reason' => $schedule->reason,
                'status' => $schedule->status,
                'backgroundColor' => $this->getStatusColor($schedule->status),
                'borderColor' => $this->getStatusColor($schedule->status),
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'driver_id' => $driverId,
                    'vehicle_id' => $schedule->vehicle_id,
                    'requester_id' => $schedule->user_id,
                    'destination' => $schedule->destination,
                    'reason' => $schedule->reason,
                    'remarks' => $schedule->remarks
                ]
            ];
        });

        return view('drivers-calendar.drivers-calendar', compact('driverSchedules', 'calendarEvents'));
    }

    private function getStatusColor($status)
    {
        switch ($status) {
            case 'approved':
                return '#10b981'; // Green
            case 'pending':
                return '#f59e0b'; // Yellow
            case 'rejected':
                return '#ef4444'; // Red
            case 'completed':
                return '#3b82f6'; // Blue
            default:
                return '#6b7280'; // Gray
        }
    }
}
