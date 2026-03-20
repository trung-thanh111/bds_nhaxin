<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('properties')->insert([
            'id' => 1,
            'title' => 'Biệt Thự Sân Vườn Cao Cấp — Khu Nam Sài Gòn',
            'slug' => 'biet-thu-san-vuon-cao-cap-khu-nam-sai-gon',
            'tagline' => 'Sống Đẳng Cấp — Nghỉ Dưỡng Tại Nhà',
            'description_short' => 'Căn biệt thự tọa lạc tại trung tâm khu đô thị Phú Mỹ Hưng, thiết kế hiện đại kết hợp phong cách nhiệt đới, tràn ngập ánh sáng tự nhiên và cây xanh.',
            'description' => 'Căn biệt thự đặc biệt này mang đến không gian sống tinh tế với diện tích rộng rãi 155m², nội thất tràn sáng tự nhiên, thiết kế hiện đại hòa quyện phong cách nhiệt đới ấm cúng. Từng góc không gian được thiết kế tỉ mỉ — nơi hoàn hảo cho sinh hoạt thường ngày, thư giãn cuối tuần và những khoảnh khắc đáng nhớ cùng gia đình. Hệ thống smart home tích hợp, năng lượng mặt trời và hồ bơi riêng tư là điểm nhấn nổi bật.',
            'price' => 28.50,
            'price_unit' => 'tỷ',
            'publish' => 2,
            'area_sqm' => 155.00,
            'bedrooms' => 5,
            'bathrooms' => 5,
            'parking_spots' => 3,
            'floors' => 2,
            'address' => '18 Nguyễn Văn Linh, Khu đô thị Phú Mỹ Hưng, Tân Phong, Quận 7',
            'district' => 'Quận 7',
            'city' => 'Thành phố Hồ Chí Minh',
            'latitude' => 10.73116800,
            'longitude' => 106.71952400,
            'year_built' => 2022,
            'video_tour_url' => 'https://www.youtube.com/watch?v=JMyl8K2voHU',
            'image' => 'userfiles/image/bds/du-lich-ben-thuong-hai.jpg',
            'seo_title' => 'Biệt Thự Sân Vườn 5PN Phú Mỹ Hưng Q7 — 28.5 Tỷ | HomePark',
            'seo_description' => 'Biệt thự cao cấp 155m² tại Phú Mỹ Hưng, 5 phòng ngủ, hồ bơi riêng, smart home, năng lượng mặt trời. Liên hệ ngay để đặt lịch xem nhà miễn phí.',
            'created_at' => '2025-01-10 08:00:00',
            'updated_at' => '2025-06-15 14:30:00'
        ]);
    }
}
