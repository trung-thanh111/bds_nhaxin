<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Tên / tiêu đề bất động sản');
            $table->string('slug')->unique()->comment('URL thân thiện SEO');
            $table->string('tagline')->nullable()->comment('Câu slogan ngắn');
            $table->text('description_short')->nullable()->comment('Mô tả ngắn hiển thị ở Hero section');
            $table->longText('description')->nullable()->comment('Mô tả chi tiết đầy đủ');
            $table->decimal('price', 15, 2)->comment('Giá bán / cho thuê (VND)');
            $table->enum('price_unit', ['tỷ', 'triệu', 'usd'])->default('tỷ')->comment('Đơn vị tiền tệ hiển thị');
            $table->tinyInteger('publish')->default(2)->comment('Trạng thái: ẩn=0, nháp=1, hiển thị=2');
            $table->decimal('area_sqm', 8, 2)->comment('Tổng diện tích (m²)');
            $table->tinyInteger('bedrooms')->comment('Số phòng ngủ');
            $table->tinyInteger('bathrooms')->comment('Số phòng tắm');
            $table->tinyInteger('parking_spots')->default(0)->comment('Số chỗ đỗ xe');
            $table->tinyInteger('floors')->default(1)->comment('Số tầng');
            $table->string('address', 500)->comment('Địa chỉ đầy đủ');
            $table->string('district', 100)->comment('Quận / Huyện');
            $table->string('city', 100)->comment('Thành phố / Tỉnh');
            $table->decimal('latitude', 10, 8)->nullable()->comment('Tọa độ GPS — vĩ độ');
            $table->decimal('longitude', 11, 8)->nullable()->comment('Tọa độ GPS — kinh độ');
            $table->year('year_built')->nullable()->comment('Năm xây dựng / hoàn công');
            $table->string('video_tour_url', 500)->nullable()->comment('Link YouTube video tour 360°');
            $table->string('image', 500)->nullable()->comment('Ảnh đại diện Hero Banner');
            $table->string('seo_title')->nullable()->comment('Tiêu đề SEO tùy chỉnh');
            $table->text('seo_description')->nullable()->comment('Meta description cho SEO');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
