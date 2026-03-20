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
        Schema::table('real_estates', function (Blueprint $table) {
            if (!Schema::hasColumn('real_estates', 'is_corner_lot')) {
                $table->boolean('is_corner_lot')->default(false)->after('land_type');
            }
            if (!Schema::hasColumn('real_estates', 'is_main_road')) {
                $table->boolean('is_main_road')->default(false)->after('is_corner_lot');
            }
            if (!Schema::hasColumn('real_estates', 'has_basement')) {
                $table->boolean('has_basement')->default(false)->after('total_floors');
            }
            if (!Schema::hasColumn('real_estates', 'has_rooftop')) {
                $table->boolean('has_rooftop')->default(false)->after('has_basement');
            }
            if (!Schema::hasColumn('real_estates', 'has_garage')) {
                $table->boolean('has_garage')->default(false)->after('has_rooftop');
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'real_estate_id')) {
                $table->unsignedBigInteger('real_estate_id')->nullable()->after('id');
                $table->foreign('real_estate_id')->references('id')->on('real_estates')->onDelete('set null');
            }
            if (!Schema::hasColumn('projects', 'project_catalogue_id')) {
                $table->unsignedBigInteger('project_catalogue_id')->nullable()->after('real_estate_id');
                $table->foreign('project_catalogue_id')->references('id')->on('project_catalogues')->onDelete('set null');
            }

            if (Schema::hasColumn('projects', 'image') && !Schema::hasColumn('projects', 'cover_image')) {
                $table->renameColumn('image', 'cover_image');
            }
        });
        Schema::dropIfExists('real_estate_catalogue_real_estate');
        Schema::dropIfExists('project_catalogue_project');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
