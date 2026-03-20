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
        Schema::create('floorplan_language', function (Blueprint $table) {
            $table->unsignedBigInteger('floorplan_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name');
            $table->text('description')->nullable();
            
            $table->foreign('floorplan_id')->references('id')->on('floorplans')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            $table->primary(['floorplan_id', 'language_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floorplan_language');
    }
};
