<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RealEstateCatalogue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RealEstateCatalogueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('real_estate_catalogues')->truncate();
        DB::table('real_estate_catalogue_language')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $userId = 1;
        $languageId = 1;

        $categories = [
            [
                'name' => 'Bất động sản Bán',
                'children' => [
                    'Căn hộ',
                    'Duplex',
                    'Penthouse',
                    'Shop House',
                    'Nhà riêng',
                    'Biệt thự',
                    'Liền kề',
                    'Nhà mặt phố',
                    'Shophouse khối đế',
                    'Condotel',
                    'Studio',
                    'Đất nền dự án',
                    'Đất thổ cư',
                    'Kho, xưởng'
                ]
            ],
            [
                'name' => 'Bất động sản Cho thuê',
                'children' => [
                    'Cho thuê Căn hộ',
                    'Cho thuê Nhà riêng',
                    'Cho thuê Nhà mặt phố',
                    'Cho thuê Nhà trọ, Phòng trọ',
                    'Cho thuê Văn phòng',
                    'Cho thuê Cửa hàng, Ki-ốt',
                    'Cho thuê Kho, Xưởng, Đất'
                ]
            ]
        ];

        $lft = 1;
        foreach ($categories as $idx => $cat) {
            $childCount = count($cat['children']);
            $rgt = $lft + ($childCount * 2) + 1;
            
            $parent = RealEstateCatalogue::create([
                'parent_id' => 0,
                'lft' => $lft,
                'rgt' => $rgt,
                'level' => 1,
                'publish' => 2,
                'user_id' => $userId,
                'order' => $idx + 1,
            ]);

            $parent->languages()->attach($languageId, [
                'name' => $cat['name'],
                'canonical' => Str::slug($cat['name']),
                'description' => $cat['name'],
                'content' => $cat['name'],
                'meta_title' => $cat['name'],
                'meta_keyword' => $cat['name'],
                'meta_description' => $cat['name'],
            ]);

            $childLft = $lft + 1;
            foreach ($cat['children'] as $cIdx => $childName) {
                $childRgt = $childLft + 1;
                $child = RealEstateCatalogue::create([
                    'parent_id' => $parent->id,
                    'lft' => $childLft,
                    'rgt' => $childRgt,
                    'level' => 2,
                    'publish' => 2,
                    'user_id' => $userId,
                    'order' => $cIdx + 1,
                ]);

                $child->languages()->attach($languageId, [
                    'name' => $childName,
                    'canonical' => Str::slug($childName),
                    'description' => $childName,
                    'content' => $childName,
                    'meta_title' => $childName,
                    'meta_keyword' => $childName,
                    'meta_description' => $childName,
                ]);

                $childLft = $childRgt + 1;
            }

            $lft = $rgt + 1;
        }

        $this->command->info('RealEstateCatalogueSeeder completed successfully.');
    }
}
