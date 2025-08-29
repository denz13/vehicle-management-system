<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\tbl_reserve_vehicle;
use App\Models\tbl_vehicle;
use App\Models\User;
use App\Models\tbl_reservation_type;

class ReserveVehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing data for relationships
        $vehicles = tbl_vehicle::take(3)->get();
        $users = User::take(5)->get();
        $reservationTypes = tbl_reservation_type::take(3)->get();
        
        if ($vehicles->count() < 1 || $users->count() < 2 || $reservationTypes->count() < 1) {
            $this->command->info('Need vehicles, users, and reservation types to seed reserve vehicles. Skipping...');
            return;
        }

        $sampleReservations = [
            [
                'vehicle_id' => $vehicles[0]->id,
                'user_id' => $users[0]->id,
                'requested_name' => 'John Doe',
                'destination' => 'City Hall',
                'longitude' => '120.9842',
                'latitude' => '14.5995',
                'driver' => 'Mike Johnson',
                'driver_user_id' => $users[1]->id,
                'start_datetime' => now()->addDays(1)->setTime(9, 0),
                'end_datetime' => now()->addDays(1)->setTime(17, 0),
                'reason' => 'Official meeting with city officials',
                'remarks' => 'Please ensure vehicle is clean and fueled',
                'reservation_type_id' => $reservationTypes[0]->id,
                'qrcode' => 'QR001' . time(),
                'status' => 'pending'
            ],
            [
                'vehicle_id' => $vehicles->count() > 1 ? $vehicles[1]->id : $vehicles[0]->id,
                'user_id' => $users[1]->id,
                'requested_name' => 'Jane Smith',
                'destination' => 'Airport',
                'longitude' => '121.0194',
                'latitude' => '14.5086',
                'driver' => 'John Doe',
                'driver_user_id' => $users[0]->id,
                'start_datetime' => now()->addDays(2)->setTime(6, 0),
                'end_datetime' => now()->addDays(2)->setTime(18, 0),
                'reason' => 'Airport pickup for visiting executives',
                'remarks' => 'International flight arrival',
                'reservation_type_id' => $reservationTypes->count() > 1 ? $reservationTypes[1]->id : $reservationTypes[0]->id,
                'qrcode' => 'QR002' . time(),
                'status' => 'approved'
            ],
            [
                'vehicle_id' => $vehicles->count() > 2 ? $vehicles[2]->id : $vehicles[0]->id,
                'user_id' => $users[2]->id ?? $users[0]->id,
                'requested_name' => 'Bob Wilson',
                'destination' => 'Training Center',
                'longitude' => '120.9500',
                'latitude' => '14.5500',
                'driver' => 'Bob Wilson',
                'driver_user_id' => $users[2]->id ?? $users[0]->id,
                'start_datetime' => now()->addDays(3)->setTime(8, 0),
                'end_datetime' => now()->addDays(3)->setTime(16, 0),
                'reason' => 'Driver training session',
                'remarks' => 'New driver orientation',
                'reservation_type_id' => $reservationTypes->count() > 2 ? $reservationTypes[2]->id : $reservationTypes[0]->id,
                'qrcode' => 'QR003' . time(),
                'status' => 'completed'
            ]
        ];

        foreach ($sampleReservations as $reservationData) {
            tbl_reserve_vehicle::create($reservationData);
        }

        $this->command->info('Reserve vehicles seeded successfully!');
    }
}
