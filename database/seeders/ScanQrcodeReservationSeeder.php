<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\tbl_scan_qrcode_reservation;
use App\Models\tbl_reserve_vehicle;

class ScanQrcodeReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing data for relationships
        $reservations = tbl_reserve_vehicle::take(3)->get();
        
        if ($reservations->count() < 1) {
            $this->command->info('Need reserve vehicles to seed scan records. Skipping...');
            return;
        }

        $sampleScanRecords = [
            [
                'reserve_vehicle_id' => $reservations[0]->id,
                'workstate' => 'started',
                'logtime' => now()->subHours(2)->format('Y-m-d H:i:s')
            ],
            [
                'reserve_vehicle_id' => $reservations[0]->id,
                'workstate' => 'in_progress',
                'logtime' => now()->subHour()->format('Y-m-d H:i:s')
            ],
            [
                'reserve_vehicle_id' => $reservations[0]->id,
                'workstate' => 'completed',
                'logtime' => now()->format('Y-m-d H:i:s')
            ],
            [
                'reserve_vehicle_id' => $reservations->count() > 1 ? $reservations[1]->id : $reservations[0]->id,
                'workstate' => 'started',
                'logtime' => now()->subDays(1)->setTime(8, 0)->format('Y-m-d H:i:s')
            ],
            [
                'reserve_vehicle_id' => $reservations->count() > 1 ? $reservations[1]->id : $reservations[0]->id,
                'workstate' => 'completed',
                'logtime' => now()->subDays(1)->setTime(17, 0)->format('Y-m-d H:i:s')
            ],
            [
                'reserve_vehicle_id' => $reservations->count() > 2 ? $reservations[2]->id : $reservations[0]->id,
                'workstate' => 'started',
                'logtime' => now()->subDays(2)->setTime(9, 0)->format('Y-m-d H:i:s')
            ],
            [
                'reserve_vehicle_id' => $reservations->count() > 2 ? $reservations[2]->id : $reservations[0]->id,
                'workstate' => 'in_progress',
                'logtime' => now()->subDays(2)->setTime(12, 0)->format('Y-m-d H:i:s')
            ],
            [
                'reserve_vehicle_id' => $reservations->count() > 2 ? $reservations[2]->id : $reservations[0]->id,
                'workstate' => 'completed',
                'logtime' => now()->subDays(2)->setTime(16, 0)->format('Y-m-d H:i:s')
            ]
        ];

        foreach ($sampleScanRecords as $scanData) {
            tbl_scan_qrcode_reservation::create($scanData);
        }

        $this->command->info('Scan QR code reservations seeded successfully!');
    }
}
