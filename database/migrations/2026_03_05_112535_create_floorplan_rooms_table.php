<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('floorplan_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('floorplan_id')->comment('Liên kết tầng');
            $table->foreign('floorplan_id')->references('id')->on('floorplans')->onDelete('cascade');
            $table->string('room_name', 100)->comment('Tên phòng: Phòng Khách, Nhà Bếp...');
            $table->decimal('area_sqm', 6, 2)->comment('Diện tích phòng (m²)');
            $table->tinyInteger('sort_order')->default(0)->comment('Thứ tự hiển thị');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('floorplan_rooms');
    }
};
