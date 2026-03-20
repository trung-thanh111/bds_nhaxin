<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('floorplan_language');
        Schema::dropIfExists('floorplan_rooms');
        Schema::dropIfExists('floorplans');

        Schema::create('floorplans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('real_estate_id')->comment('Liên kết bất động sản');
            $table->foreign('real_estate_id')->references('id')->on('real_estates')->onDelete('cascade');
            $table->string('image')->nullable()->comment('Ảnh bản đồ mặt bằng');
            $table->unsignedTinyInteger('publish')->default(2)->comment('ẩn=1, hiển thị=2');
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floorplans');
    }
};
