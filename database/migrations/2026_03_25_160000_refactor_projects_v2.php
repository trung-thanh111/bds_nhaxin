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
        // 1. Update projects table
        Schema::table('projects', function (Blueprint $table) {
            // Drop old relation
            if (Schema::hasColumn('projects', 'real_estate_id')) {
                $table->dropForeign(['real_estate_id']);
                $table->dropColumn('real_estate_id');
            }

            // Add address fields (consistent with real_estates)
            $table->string('province_code', 10)->nullable()->after('project_catalogue_id');
            $table->string('province_name')->nullable()->after('province_code');
            $table->string('district_code', 10)->nullable()->after('province_name');
            $table->string('district_name')->nullable()->after('district_code');
            $table->string('ward_code', 10)->nullable()->after('district_name');
            $table->string('ward_name')->nullable()->after('ward_code');
            
            $table->string('old_province_code', 10)->nullable()->after('ward_name');
            $table->string('old_province_name')->nullable()->after('old_province_code');
            $table->string('old_district_code', 10)->nullable()->after('old_province_name');
            $table->string('old_district_name')->nullable()->after('old_district_code');
            $table->string('old_ward_code', 10)->nullable()->after('old_district_name');
            $table->string('old_ward_name')->nullable()->after('old_ward_code');

            $table->string('street')->nullable()->after('old_ward_name');
            $table->text('iframe_map')->nullable()->after('street');
            $table->string('lat', 50)->nullable()->after('iframe_map');
            $table->string('long', 50)->nullable()->after('lat');
        });

        // 2. Remove relation from real_estates
        Schema::table('real_estates', function (Blueprint $table) {
            if (Schema::hasColumn('real_estates', 'project_id')) {
                // Check if foreign key exists before dropping
                // For simplicity in this environment, we just drop the column if it's there
                // In production, usually we drop the foreign key first.
                $table->dropColumn('project_id');
            }
        });

        // 3. Create pivot tables
        Schema::create('project_amenity', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('amenity_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('amenity_id')->references('id')->on('amenities')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('project_floorplan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('floorplan_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('floorplan_id')->references('id')->on('floorplans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_floorplan');
        Schema::dropIfExists('project_amenity');

        Schema::table('real_estates', function (Blueprint $table) {
            if (!Schema::hasColumn('real_estates', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable()->after('agent_id');
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'province_code', 'province_name', 'district_code', 'district_name', 'ward_code', 'ward_name',
                'old_province_code', 'old_province_name', 'old_district_code', 'old_district_name', 'old_ward_code', 'old_ward_name',
                'street', 'iframe_map', 'lat', 'long'
            ]);
            if (!Schema::hasColumn('projects', 'real_estate_id')) {
                $table->unsignedBigInteger('real_estate_id')->nullable()->after('id');
            }
        });
    }
};
