<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApprovalWorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default approval workflow for requisitions
        $workflow = DB::table('approval_workflows')->insert([
            'name' => 'Standard Requisition Approval',
            'description' => 'Default approval workflow for requisitions with two-level approval',
            'type' => 'requisition',
            'approval_levels' => json_encode([
                1 => [
                    'approver_type' => 'role',
                    'role_id' => 2, // Assuming role ID 2 is for managers
                    'approver_id' => null,
                ],
                2 => [
                    'approver_type' => 'role',
                    'role_id' => 1, // Assuming role ID 1 is for administrators
                    'approver_id' => null,
                ],
            ]),
            'is_active' => true,
            'created_by' => 1, // Assuming user ID 1 exists
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a simple workflow for vehicle requests
        DB::table('approval_workflows')->insert([
            'name' => 'Vehicle Request Approval',
            'description' => 'Simple approval workflow for vehicle requests',
            'type' => 'vehicle_request',
            'approval_levels' => json_encode([
                1 => [
                    'approver_type' => 'role',
                    'role_id' => 2, // Manager approval
                    'approver_id' => null,
                ],
            ]),
            'is_active' => true,
            'created_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
