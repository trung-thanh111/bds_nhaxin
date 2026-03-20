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
        Schema::create('real_estates', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('real_estate_catalogue_id')->default(0);
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->string('image')->nullable();

            // Administrative units (Old & New)
            $table->string('old_province_code')->nullable();
            $table->string('old_province_name')->nullable();
            $table->string('old_district_code')->nullable();
            $table->string('old_district_name')->nullable();
            $table->string('old_ward_code')->nullable();
            $table->string('old_ward_name')->nullable();

            $table->string('province_code')->nullable();
            $table->string('province_name')->nullable();
            $table->string('district_code')->nullable();
            $table->string('district_name')->nullable();
            $table->string('ward_code')->nullable();
            $table->string('ward_name')->nullable();

            $table->string('street')->nullable();
            $table->text('iframe_map')->nullable();

            // Pricing
            $table->decimal('price', 15, 2)->nullable();
            $table->string('price_currency')->default('VND');
            $table->string('price_type')->nullable();
            $table->string('transaction_type')->default('sale');

            // Physical specs
            $table->decimal('area', 10, 2)->nullable();
            $table->decimal('usable_area', 10, 2)->nullable();
            $table->decimal('land_area', 10, 2)->nullable();
            $table->unsignedSmallInteger('year_built')->nullable();
            $table->unsignedTinyInteger('floor_count')->nullable();
            $table->string('floor')->nullable();
            $table->unsignedSmallInteger('total_floors')->nullable();
            $table->unsignedTinyInteger('bedrooms')->nullable();
            $table->unsignedTinyInteger('bathrooms')->nullable();

            // Directions & Types
            $table->string('house_direction')->nullable();
            $table->string('balcony_direction')->nullable();
            $table->string('view')->nullable();
            $table->string('ownership_type')->nullable();
            $table->string('land_type')->nullable();

            // Land specs
            $table->decimal('land_width', 8, 2)->nullable();
            $table->decimal('land_length', 8, 2)->nullable();
            $table->decimal('road_frontage', 8, 2)->nullable();
            $table->decimal('road_width', 8, 2)->nullable();

            // Building specifics
            $table->string('block_tower')->nullable();
            $table->string('apartment_code')->nullable();

            // Media
            $table->string('video_url')->nullable();
            $table->string('tour_url')->nullable();

            // Generic fields
            $table->tinyInteger('publish')->default(2);
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->tinyInteger('follow')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_estates');
    }
};
