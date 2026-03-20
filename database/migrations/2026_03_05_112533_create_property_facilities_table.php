<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('property_facilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id')->comment('Liên kết bất động sản');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->string('icon', 100)->comment('Tên icon (SVG / class)');
            $table->string('name', 150)->comment('Tên tiện ích (VD: Nhà Thông Minh)');
            $table->text('description')->nullable()->comment('Mô tả chi tiết tiện ích');
            $table->string('image', 500)->nullable()->comment('Ảnh minh họa tiện ích');
            $table->tinyInteger('sort_order')->default(0)->comment('Thứ tự hiển thị');
            $table->tinyInteger('publish')->default(2)->comment('ẩn=0, nháp=1, hiển thị=2');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_facilities');
    }
};
