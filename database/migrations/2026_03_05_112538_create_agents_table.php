<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 150)->comment('Họ và tên đầy đủ');
            $table->string('title', 100)->nullable()->comment('Chức danh: Chuyên Viên Tư Vấn BĐS');
            $table->string('phone', 20)->comment('Số điện thoại liên hệ trực tiếp');
            $table->string('email', 255)->comment('Email công việc');
            $table->string('avatar', 500)->nullable()->comment('Ảnh đại diện agent');
            $table->text('bio')->nullable()->comment('Giới thiệu bản thân tiếng Việt');
            $table->string('zalo', 20)->nullable()->comment('SĐT Zalo (có thể khác SĐT chính)');
            $table->boolean('is_primary')->default(false)->comment('Agent hiển thị chính trên hero');
            $table->tinyInteger('publish')->default(2)->comment('ẩn=0, nháp=1, hiển thị=2');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
