<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\tbl_department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'department_name' => 'Information Technology',
                'description' => 'Handles all IT-related operations and system maintenance',
                'status' => 'active'
            ],
            [
                'department_name' => 'Human Resources',
                'description' => 'Manages employee relations, recruitment, and HR policies',
                'status' => 'active'
            ],
            [
                'department_name' => 'Finance',
                'description' => 'Handles financial operations, budgeting, and accounting',
                'status' => 'active'
            ],
            [
                'department_name' => 'Marketing',
                'description' => 'Manages brand promotion, advertising, and market research',
                'status' => 'active'
            ],
            [
                'department_name' => 'Operations',
                'description' => 'Oversees daily business operations and process improvement',
                'status' => 'inactive'
            ],
            [
                'department_name' => 'Customer Service',
                'description' => 'Provides customer support and handles inquiries',
                'status' => 'active'
            ]
        ];

        foreach ($departments as $department) {
            tbl_department::create($department);
        }
    }
}
