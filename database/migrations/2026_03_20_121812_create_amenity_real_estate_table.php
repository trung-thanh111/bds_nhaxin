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
        Schema::create('amenity_real_estate', function (Blueprint $table) {
            $table->unsignedBigInteger('amenity_id');
            $table->unsignedBigInteger('real_estate_id');
            $table->foreign('amenity_id')->references('id')->on('amenities')->onDelete('cascade');
            $table->foreign('real_estate_id')->references('id')->on('real_estates')->onDelete('cascade');
            $table->primary(['amenity_id', 'real_estate_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenity_real_estate');
    }
};
