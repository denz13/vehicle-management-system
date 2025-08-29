<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\tbl_post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $samplePosts = [
            [
                'announcement_title' => 'System Maintenance Notice',
                'description' => 'The vehicle management system will be undergoing scheduled maintenance on Saturday from 2:00 AM to 6:00 AM. During this time, the system may be temporarily unavailable.',
                'status' => 'active'
            ],
            [
                'announcement_title' => 'New Vehicle Added to Fleet',
                'description' => 'We are pleased to announce the addition of a new Toyota Hiace van to our vehicle fleet. This vehicle is now available for reservations.',
                'status' => 'active'
            ],
            [
                'announcement_title' => 'Holiday Schedule Update',
                'description' => 'Please note that vehicle reservations will be limited during the upcoming holiday season. Plan your trips accordingly.',
                'status' => 'active'
            ],
            [
                'announcement_title' => 'Driver Safety Training',
                'description' => 'Mandatory driver safety training session will be held next week. All drivers must attend to maintain their driving privileges.',
                'status' => 'active'
            ],
            [
                'announcement_title' => 'Fuel Efficiency Tips',
                'description' => 'Tips for improving fuel efficiency: maintain proper tire pressure, avoid aggressive driving, and plan routes efficiently.',
                'status' => 'inactive'
            ]
        ];

        foreach ($samplePosts as $postData) {
            tbl_post::create($postData);
        }

        $this->command->info('Posts seeded successfully!');
    }
}
