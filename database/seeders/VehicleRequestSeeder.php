<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample vehicle requests
        DB::table('vehicle_requests')->insert([
            [
                'request_number' => 'VR-2024-001',
                'requested_by' => 1, // Assuming user ID 1 exists
                'vehicle_type' => 'Sedan',
                'vehicle_purpose' => 'Official Meeting',
                'request_date' => now()->subDays(5),
                'required_date' => now()->subDays(3),
                'required_time' => '09:00:00',
                'destination' => 'Kampala City',
                'description' => 'Meeting with clients at Kampala office',
                'status' => 'approved',
                'approved_by' => 1,
                'approved_at' => now()->subDays(4),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4),
            ],
            [
                'request_number' => 'VR-2024-002',
                'requested_by' => 1,
                'vehicle_type' => 'SUV',
                'vehicle_purpose' => 'Field Visit',
                'request_date' => now()->subDays(3),
                'required_date' => now()->subDays(1),
                'required_time' => '08:00:00',
                'destination' => 'Jinja District',
                'description' => 'Site inspection for new project',
                'status' => 'completed',
                'approved_by' => 1,
                'approved_at' => now()->subDays(2),
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(1),
            ],
            [
                'request_number' => 'VR-2024-003',
                'requested_by' => 1,
                'vehicle_type' => 'Pickup Truck',
                'vehicle_purpose' => 'Delivery',
                'request_date' => now()->subDays(2),
                'required_date' => now(),
                'required_time' => '10:00:00',
                'destination' => 'Entebbe',
                'description' => 'Deliver equipment to airport',
                'status' => 'pending',
                'approved_by' => null,
                'approved_at' => null,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ]);

        // Sample vehicle returns
        DB::table('vehicle_returns')->insert([
            [
                'vehicle_request_id' => 2, // Reference to the completed request
                'return_date' => now()->subDays(1),
                'return_time' => '17:00:00',
                'return_location' => 'Jinja District',
                'mileage_covered' => 150,
                'return_notes' => 'Vehicle returned in good condition',
                'vehicle_condition' => 'good',
                'damage_description' => null,
                'returned_by' => 1,
                'received_by' => 1,
                'received_at' => now()->subDays(1),
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
        ]);
    }
}
