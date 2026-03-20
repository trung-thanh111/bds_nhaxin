<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\AttributeCatalogue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('attributes')->truncate();
        DB::table('attribute_catalogues')->truncate();
        DB::table('attribute_language')->truncate();
        DB::table('attribute_catalogue_language')->truncate();
        DB::table('attribute_catalogue_attribute')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $userId = 1;
        $languageId = 1;

        $catalogues = [
            [
                'name' => 'Lầu',
                'code' => 'lau',
                'attributes' => array_merge(['Trung bình', 'Cao', 'Thấp'], array_map(fn($i) => "Lầu $i", range(1, 40)))
            ],
            [
                'name' => 'Loại giá',
                'code' => 'loai_gia',
                'attributes' => ['Tổng', 'Thỏa thuận', '/tháng', '/m2', '/năm']
            ],
            [
                'name' => 'Hướng ban công',
                'code' => 'huong_ban_cong',
                'attributes' => ['Đông', 'Tây', 'Nam', 'Bắc', 'Đông Bắc', 'Tây Bắc', 'Đông Nam', 'Tây Nam']
            ],
            [
                'name' => 'Hướng nhà',
                'code' => 'huong_nha',
                'attributes' => ['Đông', 'Tây', 'Nam', 'Bắc', 'Đông Bắc', 'Tây Bắc', 'Đông Nam', 'Tây Nam']
            ],
            [
                'name' => 'Nội thất',
                'code' => 'noi_that',
                'attributes' => ['Không nội thất', 'Nội thất cơ bản', 'Nội thất đầy đủ']
            ],
            [
                'name' => 'Pháp lý',
                'code' => 'phap_ly',
                'attributes' => ['Sổ hồng/Sổ đỏ', 'Hợp đồng mua bán (HĐMB)', 'Giấy tờ tay', 'Đang chờ sổ', 'Bản vẽ trích lục']
            ]
        ];

        $catalogues = array_reverse($catalogues);

        foreach ($catalogues as $idx => $cat) {
            $attributeCatalogue = AttributeCatalogue::create([
                'parent_id' => 0,
                'lft' => ($idx * 2) + 1,
                'rgt' => ($idx * 2) + 2,
                'level' => 1,
                'publish' => 2,
                'user_id' => $userId,
                'order' => $idx + 1,
                'code' => $cat['code']
            ]);

            $attributeCatalogue->languages()->attach($languageId, [
                'name' => $cat['name'],
                'canonical' => Str::slug($cat['name']),
                'description' => $cat['name'],
                'content' => $cat['name'],
                'meta_title' => $cat['name'],
                'meta_keyword' => $cat['name'],
                'meta_description' => $cat['name'],
            ]);

            foreach ($cat['attributes'] as $order => $attrName) {
                $attrNameStr = (string)$attrName;
                $attribute = Attribute::create([
                    'attribute_catalogue_id' => $attributeCatalogue->id,
                    'user_id' => $userId,
                    'publish' => 2,
                    'order' => $order + 1,
                    'code' => generate_code($attrNameStr)
                ]);

                $attribute->languages()->attach($languageId, [
                    'name' => $attrNameStr,
                    'canonical' => Str::slug($attrNameStr) . '-' . Str::random(3),
                    'description' => $attrNameStr,
                    'content' => $attrNameStr,
                    'meta_title' => $attrNameStr,
                    'meta_keyword' => $attrNameStr,
                    'meta_description' => $attrNameStr,
                ]);

                // Link to its main catalogue via pivot if required by system logic
                $attribute->attribute_catalogues()->attach($attributeCatalogue->id);
            }
        }

        $this->command->info('AttributeSeeder completed successfully.');
    }
}
