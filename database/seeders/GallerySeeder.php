<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('galleries')->truncate();

        $galleries = [
            [
                'property_id' => 1,
                'image' => 'uploads/gallery/phong-khach-goc-rong.webp',
                'album' => json_encode([
                    'uploads/gallery/phong-khach-goc-rong.webp',
                    'uploads/gallery/phong-ngu-chinh-view.webp',
                    'uploads/gallery/phong-an-hien-dai.webp',
                    'uploads/gallery/nha-bep-island.webp',
                    'uploads/gallery/phong-tam-cao-cap.webp',
                    'uploads/gallery/mat-tien-biet-thu.webp',
                    'uploads/gallery/san-vuon-xanh.webp',
                    'uploads/gallery/ho-boi-dem.webp',
                    'uploads/gallery/smart-home-panel.webp',
                    'uploads/gallery/solar-panel-mai.webp',
                ]),
                'publish' => 2,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('galleries')->insert($galleries);
    }
}
