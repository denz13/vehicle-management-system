<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\tbl_vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            [
                'vehicle_name' => 'Toyota Hiace',
                'vehicle_color' => 'White',
                'model' => 'Hiace 2020',
                'plate_number' => 'ABC-1234',
                'capacity' => 15,
                'date_acquired' => '2020-01-15',
                'status' => 'active'
            ],
            [
                'vehicle_name' => 'Nissan Urvan',
                'vehicle_color' => 'Silver',
                'model' => 'Urvan 2019',
                'plate_number' => 'XYZ-5678',
                'capacity' => 12,
                'date_acquired' => '2019-06-20',
                'status' => 'active'
            ],
            [
                'vehicle_name' => 'Mitsubishi L300',
                'vehicle_color' => 'Blue',
                'model' => 'L300 2018',
                'plate_number' => 'DEF-9012',
                'capacity' => 8,
                'date_acquired' => '2018-03-10',
                'status' => 'maintenance'
            ],
            [
                'vehicle_name' => 'Toyota Coaster',
                'vehicle_color' => 'Green',
                'model' => 'Coaster 2021',
                'plate_number' => 'GHI-3456',
                'capacity' => 25,
                'date_acquired' => '2021-09-05',
                'status' => 'active'
            ],
            [
                'vehicle_name' => 'Isuzu N-Series',
                'vehicle_color' => 'Red',
                'model' => 'N-Series 2017',
                'plate_number' => 'JKL-7890',
                'capacity' => 6,
                'date_acquired' => '2017-12-15',
                'status' => 'inactive'
            ],
            [
                'vehicle_name' => 'Ford Transit',
                'vehicle_color' => 'Black',
                'model' => 'Transit 2022',
                'plate_number' => 'MNO-2345',
                'capacity' => 18,
                'date_acquired' => '2022-02-28',
                'status' => 'active'
            ]
        ];

        foreach ($vehicles as $vehicle) {
            tbl_vehicle::create($vehicle);
        }
    }
}
