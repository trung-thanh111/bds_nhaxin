<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FloorplanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('floorplans')->insert([
            ['id' => 1, 'property_id' => 1, 'floor_number' => 1, 'floor_label' => 'Tầng 1', 'publish' => 2, 'plan_image' => 'uploads/properties/1/floorplan-tang1.webp'],
            ['id' => 2, 'property_id' => 1, 'floor_number' => 2, 'floor_label' => 'Tầng 2', 'publish' => 2, 'plan_image' => 'uploads/properties/1/floorplan-tang2.webp']
        ]);
    }
}
