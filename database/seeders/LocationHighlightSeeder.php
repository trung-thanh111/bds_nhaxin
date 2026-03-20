<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationHighlightSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('location_highlights')->insert([
            ['id' => 1, 'property_id' => 1, 'category' => 'grocery', 'name' => 'VinMart Nguyễn Văn Linh', 'distance_text' => '4 phút đi bộ', 'sort_order' => 1, 'publish' => 2, 'description' => 'Siêu thị cao cấp, thực phẩm tươi & nhập khẩu'],
            ['id' => 2, 'property_id' => 1, 'category' => 'grocery', 'name' => 'Co.opmart Nam Sài Gòn', 'distance_text' => '9 phút đi bộ', 'sort_order' => 2, 'publish' => 2, 'description' => 'Siêu thị bình dân, nhiều khuyến mãi hàng tuần'],
            ['id' => 3, 'property_id' => 1, 'category' => 'dining', 'name' => 'Phố Ẩm Thực Crescent Mall', 'distance_text' => '7 phút đi bộ', 'sort_order' => 3, 'publish' => 2, 'description' => '30+ nhà hàng đa quốc gia trong trung tâm thương mại'],
            ['id' => 4, 'property_id' => 1, 'category' => 'dining', 'name' => 'Nhà Hàng Bến Cảng Xưa', 'distance_text' => '12 phút lái xe', 'sort_order' => 4, 'publish' => 2, 'description' => 'Hải sản tươi sống, sân vườn ngoài trời, phong cách Đông Dương'],
            ['id' => 5, 'property_id' => 1, 'category' => 'transport', 'name' => 'Metro Tuyến 1 — Ga PMH', 'distance_text' => '6 phút đi bộ', 'sort_order' => 5, 'publish' => 2, 'description' => 'Kết nối Bến Thành — Suối Tiên, giảm tải ách tắc'],
            ['id' => 6, 'property_id' => 1, 'category' => 'transport', 'name' => 'Cao tốc TP.HCM — Long Thành', 'distance_text' => '8 phút lái xe', 'sort_order' => 6, 'publish' => 2, 'description' => 'Kết nối sân bay Long Thành và các tỉnh phía Đông Nam'],
            ['id' => 7, 'property_id' => 1, 'category' => 'education', 'name' => 'Trường Quốc tế ISSP', 'distance_text' => '5 phút lái xe', 'sort_order' => 7, 'publish' => 2, 'description' => 'IB & Cambridge K–12, dạy hoàn toàn bằng tiếng Anh'],
            ['id' => 8, 'property_id' => 1, 'category' => 'education', 'name' => 'Đại học RMIT Việt Nam', 'distance_text' => '10 phút lái xe', 'sort_order' => 8, 'publish' => 2, 'description' => 'Trường Úc danh tiếng, chương trình chuẩn quốc tế'],
            ['id' => 9, 'property_id' => 1, 'category' => 'hospital', 'name' => 'BV Quốc Tế Hạnh Phúc', 'distance_text' => '8 phút lái xe', 'sort_order' => 9, 'publish' => 2, 'description' => 'Phụ sản & nhi khoa hàng đầu, tiêu chuẩn JCI'],
            ['id' => 10, 'property_id' => 1, 'category' => 'hospital', 'name' => 'Phòng Khám Đa Khoa Sài Gòn', 'distance_text' => '6 phút đi bộ', 'sort_order' => 10, 'publish' => 2, 'description' => 'Khám 24/7, có bác sĩ nước ngoài, hẹn online'],
            ['id' => 11, 'property_id' => 1, 'category' => 'park', 'name' => 'Công Viên Hồ Bán Nguyệt PMH', 'distance_text' => '8 phút đi bộ', 'sort_order' => 11, 'publish' => 2, 'description' => 'Hồ nhân tạo đẹp nhất PMH, đường dạo bộ & café'],
            ['id' => 12, 'property_id' => 1, 'category' => 'park', 'name' => 'Khu Sinh Thái Rừng Cần Giờ', 'distance_text' => '45 phút lái xe', 'sort_order' => 12, 'publish' => 2, 'description' => 'Khu dự trữ sinh quyển UNESCO, dã ngoại cuối tuần']
        ]);
    }
}
