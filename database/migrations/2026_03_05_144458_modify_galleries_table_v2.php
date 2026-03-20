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
        Schema::table('galleries', function (Blueprint $table) {
            $table->text('album')->nullable()->after('property_id')->comment('Danh sách ảnh bộ sưu tập (JSON)');
            $table->dropColumn(['thumbnail', 'caption', 'category', 'alt_text', 'is_featured', 'sort_order', 'uploaded_at']);
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn(['album']);
            $table->string('thumbnail', 500)->nullable();
            $table->string('caption', 200)->nullable();
            $table->enum('category', ['interior', 'exterior', 'amenities', 'other'])->default('other');
            $table->string('alt_text', 255)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamp('uploaded_at')->useCurrent();
        });
    }
};
