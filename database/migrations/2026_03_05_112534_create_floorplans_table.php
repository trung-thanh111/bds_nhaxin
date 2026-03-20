<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('floorplans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id')->comment('Liên kết bất động sản');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->tinyInteger('floor_number')->comment('Số tầng (1, 2, 3...)');
            $table->string('floor_label', 50)->comment('Nhãn hiển thị: Tầng 1, Tầng 2...');
            $table->string('plan_image', 500)->comment('Ảnh bản đồ mặt bằng');
            $table->tinyInteger('publish')->default(2)->comment('ẩn=0, nháp=1, hiển thị=2');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('floorplans');
    }
};
