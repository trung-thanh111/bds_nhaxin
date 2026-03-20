<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id')->comment('Liên kết bất động sản');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->string('image', 500)->nullable()->comment('Đường dẫn file ảnh');
            $table->string('thumbnail', 500)->nullable()->comment('Ảnh thumbnail tối ưu tốc độ');
            $table->string('caption', 200)->nullable()->comment('Chú thích ảnh tiếng Việt');
            $table->enum('category', ['interior', 'exterior', 'amenities', 'other'])->comment('Phân loại ảnh');
            $table->string('alt_text', 255)->nullable()->comment('Mô tả alt cho SEO & accessibility');
            $table->boolean('is_featured')->default(false)->comment('Hiển thị ở gallery trang chủ');
            $table->integer('sort_order')->default(0)->comment('Thứ tự sắp xếp ảnh');
            $table->timestamp('uploaded_at')->useCurrent()->comment('Thời gian upload');
            $table->tinyInteger('publish')->default(2)->comment('ẩn=0, nháp=1, hiển thị=2');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
