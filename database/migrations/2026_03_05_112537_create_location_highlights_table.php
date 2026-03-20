<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('location_highlights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id')->comment('Liên kết bất động sản');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->enum('category', ['grocery', 'dining', 'transport', 'education', 'hospital', 'park', 'other'])->comment('Loại tiện ích lân cận');
            $table->string('name', 150)->comment('Tên địa điểm tiếng Việt');
            $table->string('distance_text', 50)->comment('Khoảng cách: 5 phút đi bộ / 8 phút lái xe');
            $table->text('description')->nullable()->comment('Mô tả ngắn về địa điểm');
            $table->tinyInteger('sort_order')->default(0)->comment('Thứ tự trong danh sách');
            $table->tinyInteger('publish')->default(2)->comment('ẩn=0, nháp=1, hiển thị=2');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_highlights');
    }
};
