<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectCatalogue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectCatalogueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vô hiệu hóa ngoại khóa và truncate bảng
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('project_catalogues')->truncate();
        DB::table('project_catalogue_language')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $userId = 1;
        $languageId = 1; // Tiếng Việt

        $categories = [
            'Căn hộ chung cư',
            'Cao ốc văn phòng',
            'Trung tâm thương mại',
            'Khu đô thị mới',
            'Khu phức hợp',
            'Nhà ở xã hội',
            'Khu nghỉ dưỡng, Sinh thái',
            'Khu công nghiệp',
            'Biệt thự, liền kề',
            'Shophouse',
            'Nhà mặt phố',
            'Dự án khác'
        ];

        $lft = 1;
        foreach ($categories as $index => $name) {
            $rgt = $lft + 1;
            
            // Tạo catalogue
            $catalogue = ProjectCatalogue::create([
                'parent_id' => null,
                'name' => $name,
                'slug' => Str::slug($name),
                'lft' => $lft,
                'rgt' => $rgt,
                'level' => 1,
                'publish' => 2,
                'user_id' => $userId,
                'order' => $index + 1,
            ]);

            // Gắn ngôn ngữ
            $catalogue->languages()->attach($languageId, [
                'name' => $name,
                'canonical' => Str::slug($name),
                'description' => $name,
                'content' => $name,
                'meta_title' => $name,
                'meta_keyword' => $name,
                'meta_description' => $name,
            ]);

            $lft = $rgt + 1;
        }

        $this->command->info('ProjectCatalogueSeeder completed successfully: ' . count($categories) . ' categories added.');
    }
}
