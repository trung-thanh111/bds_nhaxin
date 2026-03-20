<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('visit_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id')->comment('Bất động sản muốn xem');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->string('full_name', 150)->comment('Họ và tên người đăng ký');
            $table->string('email', 255)->comment('Địa chỉ email liên lạc');
            $table->string('phone', 20)->comment('Số điện thoại');
            $table->date('preferred_date')->nullable()->comment('Ngày muốn xem nhà');
            $table->time('preferred_time')->comment('Giờ muốn xem: 10:00 – 20:00');
            $table->text('message')->nullable()->comment('Ghi chú / yêu cầu thêm từ khách');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending')->comment('Trạng thái xử lý');
            $table->text('admin_notes')->nullable()->comment('Ghi chú nội bộ của admin');
            $table->unsignedBigInteger('assigned_agent_id')->nullable()->comment('Nhân viên được phân công');
            $table->foreign('assigned_agent_id')->references('id')->on('agents')->onDelete('set null');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_requests');
    }
};
