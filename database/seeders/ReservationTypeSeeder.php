<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\tbl_reservation_type;

class ReservationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sampleReservationTypes = [
            [
                'reservation_name' => 'Official Business',
                'description' => 'Vehicle reservation for official business purposes, meetings, and work-related travel.',
                'status' => 'active'
            ],
            [
                'reservation_name' => 'Personal Use',
                'description' => 'Vehicle reservation for personal use during approved hours and conditions.',
                'status' => 'active'
            ],
            [
                'reservation_name' => 'Emergency',
                'description' => 'Emergency vehicle reservation for urgent situations requiring immediate transportation.',
                'status' => 'active'
            ],
            [
                'reservation_name' => 'Training',
                'description' => 'Vehicle reservation for driver training and certification purposes.',
                'status' => 'active'
            ],
            [
                'reservation_name' => 'Maintenance',
                'description' => 'Vehicle reservation for maintenance and repair purposes.',
                'status' => 'inactive'
            ]
        ];

        foreach ($sampleReservationTypes as $typeData) {
            tbl_reservation_type::create($typeData);
        }

        $this->command->info('Reservation types seeded successfully!');
    }
}
