<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactRequestSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contact_requests')->insert([
            [
                'id' => 1, 
                'project_id' => 1, 
                'full_name' => 'Phạm Văn Đức', 
                'phone' => '0907 111 222', 
                'email' => 'duc.pham@example.com', 
                'subject' => 'Tư vấn dự án', 
                'content' => 'Tôi muốn tìm hiểu thêm về dự án này.', 
                'status' => 'confirmed', 
                'assigned_agent_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2, 
                'project_id' => 1, 
                'full_name' => 'Ngô Thị Lan Anh', 
                'phone' => '0918 333 444', 
                'email' => 'lananh@example.com', 
                'subject' => 'Hỏi giá', 
                'content' => 'Gửi cho tôi bảng giá chi tiết nhé.', 
                'status' => 'pending', 
                'assigned_agent_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
