<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisitRequestSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('visit_requests')->insert([
            ['id' => 1, 'property_id' => 1, 'full_name' => 'Phạm Văn Đức', 'phone' => '0907 111 222', 'email' => 'duc.pham@example.com', 'preferred_date' => '2025-07-15', 'preferred_time' => '10:00:00', 'status' => 'confirmed', 'assigned_agent_id' => 1],
            ['id' => 2, 'property_id' => 1, 'full_name' => 'Ngô Thị Lan Anh', 'phone' => '0918 333 444', 'email' => 'lananh@example.com', 'preferred_date' => '2025-07-16', 'preferred_time' => '14:00:00', 'status' => 'pending', 'assigned_agent_id' => 2],
            ['id' => 3, 'property_id' => 1, 'full_name' => 'Bùi Thanh Hải', 'phone' => '0903 555 666', 'email' => 'hai.bui@example.com', 'preferred_date' => '2025-07-10', 'preferred_time' => '16:00:00', 'status' => 'completed', 'assigned_agent_id' => 1],
            ['id' => 4, 'property_id' => 1, 'full_name' => 'Võ Minh Trí', 'phone' => '0932 777 888', 'email' => 'minhtri@example.com', 'preferred_date' => '2025-07-17', 'preferred_time' => '11:00:00', 'status' => 'cancelled', 'assigned_agent_id' => 3],
            ['id' => 5, 'property_id' => 1, 'full_name' => 'Đặng Thị Quỳnh Như', 'phone' => '0945 999 000', 'email' => 'quynhnhu@example.com', 'preferred_date' => '2025-07-18', 'preferred_time' => '15:00:00', 'status' => 'pending', 'assigned_agent_id' => 2]
        ]);
    }
}
