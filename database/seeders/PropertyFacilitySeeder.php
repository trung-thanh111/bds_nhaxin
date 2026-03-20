<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyFacilitySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('property_facilities')->insert([
            ['id' => 1, 'property_id' => 1, 'icon' => 'icon-smart-home', 'name' => 'Nhà Thông Minh', 'sort_order' => 1, 'publish' => 2, 'description' => 'App điều khiển: đèn, điều hòa, rèm, khóa — chuẩn Matter & HomeKit'],
            ['id' => 2, 'property_id' => 1, 'icon' => 'icon-solar', 'name' => 'Năng Lượng Mặt Trời', 'sort_order' => 2, 'publish' => 2, 'description' => '16 tấm pin 8kWp, lưu trữ 10kWh — giảm 70% hóa đơn điện hàng tháng'],
            ['id' => 3, 'property_id' => 1, 'icon' => 'icon-pool', 'name' => 'Hồ Bơi Riêng Tư', 'sort_order' => 3, 'publish' => 2, 'description' => 'Hồ bơi 6m×3m, lọc nước muối, đèn LED đổi màu, hệ thống sưởi ấm'],
            ['id' => 4, 'property_id' => 1, 'icon' => 'icon-security', 'name' => 'Bảo Mật Nhà Ở', 'sort_order' => 4, 'publish' => 2, 'description' => '8 camera IP 4K, cảm biến ngoại vi, khóa vân tay + mã số, chuông hình']
        ]);
    }
}
