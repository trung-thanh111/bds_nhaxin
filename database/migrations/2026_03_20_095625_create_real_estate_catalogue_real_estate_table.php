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
        Schema::create('real_estate_catalogue_real_estate', function (Blueprint $table) {
            $table->unsignedBigInteger('real_estate_id');
            $table->unsignedBigInteger('real_estate_catalogue_id');

            $table->foreign('real_estate_id', 're_id_fk')->references('id')->on('real_estates')->onDelete('cascade');
            $table->foreign('real_estate_catalogue_id', 're_cat_id_fk')->references('id')->on('real_estate_catalogues')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_estate_catalogue_real_estate');
    }
};
