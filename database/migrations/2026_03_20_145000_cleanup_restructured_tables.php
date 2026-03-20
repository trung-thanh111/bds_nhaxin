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
            $cols = [
                'area',
                'area_use',
                'area_land',
                'bedrooms',
                'bathrooms',
                'floors',
                'floor_number',
                'direction',
                'balcony_direction',
                'year_built',
                'province_code',
                'district_code',
                'ward_code',
                'address',
                'latitude',
                'longitude',
                'iframe_map',
                'album',
                'province_name',
                'district_name',
                'ward_name',
                'province_new_code',
                'province_new_name',
                'ward_new_code',
                'ward_new_name',
                'legal_status',
                'furniture_status',
                'has_elevator',
                'has_pool',
                'has_parking',
                'has_security',
                'has_balcony',
                'has_gym',
                'has_ac',
                'has_wifi'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('projects', $col)) {
                    $table->dropColumn($col);
                }
            }

            if (Schema::hasColumn('projects', 'catalogue_id')) {
                $table->dropColumn('catalogue_id');
            }
        });

        Schema::table('real_estates', function (Blueprint $table) {
            $cols = ['price', 'price_currency', 'price_type', 'transaction_type'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('real_estates', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
