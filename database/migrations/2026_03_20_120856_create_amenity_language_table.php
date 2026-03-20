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
        Schema::create('amenity_language', function (Blueprint $table) {
            $table->unsignedBigInteger('amenity_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical')->unique();
            
            $table->foreign('amenity_id', 'al_amenity_id_foreign')->references('id')->on('amenities')->onDelete('cascade');
            $table->foreign('language_id', 'al_language_id_foreign')->references('id')->on('languages')->onDelete('cascade');
            $table->primary(['amenity_id', 'language_id'], 'al_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenity_language');
    }
};
