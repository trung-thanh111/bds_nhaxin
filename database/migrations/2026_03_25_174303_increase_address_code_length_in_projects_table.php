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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('province_code', 50)->nullable()->change();
            $table->string('district_code', 50)->nullable()->change();
            $table->string('ward_code', 50)->nullable()->change();
            
            $table->string('old_province_code', 50)->nullable()->change();
            $table->string('old_district_code', 50)->nullable()->change();
            $table->string('old_ward_code', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('province_code', 10)->nullable()->change();
            $table->string('district_code', 10)->nullable()->change();
            $table->string('ward_code', 10)->nullable()->change();
            
            $table->string('old_province_code', 10)->nullable()->change();
            $table->string('old_district_code', 10)->nullable()->change();
            $table->string('old_ward_code', 10)->nullable()->change();
        });
    }
};
