<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('agents')->insert([
            ['id' => 1, 'full_name' => 'Nguyễn Thị Hương', 'title' => 'Trưởng Phòng Tư Vấn BĐS', 'phone' => '0901 234 567', 'email' => 'huong.nguyen@homely.vn', 'zalo' => '0901 234 567', 'is_primary' => true, 'publish' => 2, 'bio' => '7 năm kinh nghiệm môi giới BĐS cao cấp tại TP.HCM. Chuyên phân khúc biệt thự Phú Mỹ Hưng và Quận 2. Tốt nghiệp Đại học Kinh tế TP.HCM, chứng chỉ môi giới quốc tế CIPS.'],
            ['id' => 2, 'full_name' => 'Trần Minh Khoa', 'title' => 'Chuyên Viên Tư Vấn Cao Cấp', 'phone' => '0912 345 678', 'email' => 'khoa.tran@homely.vn', 'zalo' => '0912 345 678', 'is_primary' => false, 'publish' => 2, 'bio' => '5 năm kinh nghiệm, am hiểu thị trường Nam Sài Gòn. Thành thạo tiếng Anh và tiếng Hàn — hỗ trợ khách hàng nước ngoài đặc biệt tốt.'],
            ['id' => 3, 'full_name' => 'Lê Thị Thanh Tâm', 'title' => 'Chuyên Viên Tư Vấn', 'phone' => '0938 456 789', 'email' => 'tam.le@homely.vn', 'zalo' => '0938 456 789', 'is_primary' => false, 'publish' => 2, 'bio' => '3 năm kinh nghiệm, chuyên xử lý thủ tục pháp lý BĐS, hỗ trợ vay ngân hàng và công chứng sang tên.']
        ]);
    }
}
