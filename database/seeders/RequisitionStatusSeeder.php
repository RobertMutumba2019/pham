<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequisitionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Draft',
                'description' => 'Requisition is in draft mode and not yet submitted',
                'color' => '#6c757d',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Pending',
                'description' => 'Requisition is submitted and waiting for approval',
                'color' => '#ffc107',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Approved',
                'description' => 'Requisition has been approved',
                'color' => '#28a745',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Rejected',
                'description' => 'Requisition has been rejected',
                'color' => '#dc3545',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Completed',
                'description' => 'Requisition has been completed',
                'color' => '#17a2b8',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Cancelled',
                'description' => 'Requisition has been cancelled',
                'color' => '#6f42c1',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($statuses as $status) {
            DB::table('requisition_statuses')->insert($status);
        }
    }
}
