<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\tbl_position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            [
                'position_name' => 'Software Engineer',
                'description' => 'Develops software applications and systems',
                'status' => 'active'
            ],
            [
                'position_name' => 'Project Manager',
                'description' => 'Manages project timelines and resources',
                'status' => 'active'
            ],
            [
                'position_name' => 'System Administrator',
                'description' => 'Maintains and manages IT infrastructure',
                'status' => 'active'
            ],
            [
                'position_name' => 'Data Analyst',
                'description' => 'Analyzes data to provide insights',
                'status' => 'inactive'
            ],
            [
                'position_name' => 'UI/UX Designer',
                'description' => 'Designs user interfaces and experiences',
                'status' => 'active'
            ]
        ];

        foreach ($positions as $position) {
            tbl_position::create($position);
        }
    }
}
