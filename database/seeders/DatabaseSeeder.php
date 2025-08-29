<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed basic data first (no dependencies)
        $this->call(PositionSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(ReservationTypeSeeder::class);
        
        // Seed users (depends on positions and departments)
        $this->call(UserSeeder::class);
        
        // Seed vehicles (no dependencies)
        $this->call(VehicleSeeder::class);
        
        // Seed posts (no dependencies)
        $this->call(PostSeeder::class);
        
        // Seed reserve vehicles (depends on users, vehicles, and reservation types)
        $this->call(ReserveVehicleSeeder::class);
        
        // Seed passengers (depends on reserve vehicles and users)
        $this->call(ReserveVehiclePassengerSeeder::class);
        
        // Seed scan records (depends on reserve vehicles)
        $this->call(ScanQrcodeReservationSeeder::class);
        
        // Seed chat messages (depends on users)
        $this->call(ChatMessageSeeder::class);
    }
}
