<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FloorplanRoomSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('floorplan_rooms')->insert([
            ['id' => 1, 'floorplan_id' => 1, 'room_name' => 'Phòng Khách', 'area_sqm' => 20.00, 'sort_order' => 1],
            ['id' => 2, 'floorplan_id' => 1, 'room_name' => 'Phòng Ăn', 'area_sqm' => 15.00, 'sort_order' => 2],
            ['id' => 3, 'floorplan_id' => 1, 'room_name' => 'Nhà Bếp', 'area_sqm' => 15.00, 'sort_order' => 3],
            ['id' => 4, 'floorplan_id' => 1, 'room_name' => 'Nhà Xe', 'area_sqm' => 40.00, 'sort_order' => 4],
            ['id' => 5, 'floorplan_id' => 2, 'room_name' => 'Phòng Ngủ Chính', 'area_sqm' => 16.00, 'sort_order' => 1],
            ['id' => 6, 'floorplan_id' => 2, 'room_name' => 'Phòng Trẻ Em 1', 'area_sqm' => 12.00, 'sort_order' => 2],
            ['id' => 7, 'floorplan_id' => 2, 'room_name' => 'Phòng Trẻ Em 2', 'area_sqm' => 12.00, 'sort_order' => 3],
            ['id' => 8, 'floorplan_id' => 2, 'room_name' => 'Phòng Tắm', 'area_sqm' => 6.00, 'sort_order' => 4],
            ['id' => 9, 'floorplan_id' => 2, 'room_name' => 'Kho Chứa Đồ', 'area_sqm' => 4.00, 'sort_order' => 5]
        ]);
    }
}
