<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\tbl_reserve_vehicle_passenger;
use App\Models\tbl_reserve_vehicle;
use App\Models\User;

class ReserveVehiclePassengerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing data for relationships
        $reservations = tbl_reserve_vehicle::take(3)->get();
        $users = User::take(5)->get();
        
        if ($reservations->count() < 1 || $users->count() < 2) {
            $this->command->info('Need reserve vehicles and users to seed passengers. Skipping...');
            return;
        }

        $samplePassengers = [
            [
                'reserve_vehicle_id' => $reservations[0]->id,
                'passenger_id' => $users[0]->id,
                'passenger_name' => 'John Doe',
                'status' => 'confirmed'
            ],
            [
                'reserve_vehicle_id' => $reservations[0]->id,
                'passenger_id' => $users[1]->id,
                'passenger_name' => 'Jane Smith',
                'status' => 'confirmed'
            ],
            [
                'reserve_vehicle_id' => $reservations[0]->id,
                'passenger_id' => $users[2]->id ?? $users[0]->id,
                'passenger_name' => 'Bob Wilson',
                'status' => 'pending'
            ],
            [
                'reserve_vehicle_id' => $reservations->count() > 1 ? $reservations[1]->id : $reservations[0]->id,
                'passenger_id' => $users[1]->id,
                'passenger_name' => 'Jane Smith',
                'status' => 'confirmed'
            ],
            [
                'reserve_vehicle_id' => $reservations->count() > 1 ? $reservations[1]->id : $reservations[0]->id,
                'passenger_id' => $users[3]->id ?? $users[0]->id,
                'passenger_name' => 'Alice Johnson',
                'status' => 'confirmed'
            ],
            [
                'reserve_vehicle_id' => $reservations->count() > 2 ? $reservations[2]->id : $reservations[0]->id,
                'passenger_id' => $users[2]->id ?? $users[0]->id,
                'passenger_name' => 'Bob Wilson',
                'status' => 'confirmed'
            ]
        ];

        foreach ($samplePassengers as $passengerData) {
            tbl_reserve_vehicle_passenger::create($passengerData);
        }

        $this->command->info('Reserve vehicle passengers seeded successfully!');
    }
}
