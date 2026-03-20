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
        // 1. Fix Galleries
        Schema::table('galleries', function (Blueprint $table) {
            if (Schema::hasColumn('galleries', 'real_estate_id')) {
                try {
                    $table->dropForeign('galleries_property_id_foreign');
                } catch (\Exception $e) {
                }

                $table->foreign('real_estate_id')
                    ->references('id')
                    ->on('real_estates')
                    ->onDelete('cascade');
            }
        });

        Schema::table('location_highlights', function (Blueprint $table) {
            if (Schema::hasColumn('location_highlights', 'property_id')) {
                try {
                    $table->dropForeign('location_highlights_property_id_foreign');
                } catch (\Exception $e) {
                }

                $table->renameColumn('property_id', 'real_estate_id');
            }
        });
        Schema::table('location_highlights', function (Blueprint $table) {
            if (Schema::hasColumn('location_highlights', 'real_estate_id')) {
                $table->foreign('real_estate_id')
                    ->references('id')
                    ->on('real_estates')
                    ->onDelete('cascade');
            }
        });

        Schema::table('property_facilities', function (Blueprint $table) {
            if (Schema::hasColumn('property_facilities', 'property_id')) {
                try {
                    $table->dropForeign('property_facilities_property_id_foreign');
                } catch (\Exception $e) {
                }

                $table->renameColumn('property_id', 'real_estate_id');
            }
        });
        Schema::table('property_facilities', function (Blueprint $table) {
            if (Schema::hasColumn('property_facilities', 'real_estate_id')) {
                $table->foreign('real_estate_id')
                    ->references('id')
                    ->on('real_estates')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('location_highlights', function (Blueprint $table) {
            $table->dropForeign(['real_estate_id']);
            $table->renameColumn('real_estate_id', 'property_id');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });

        Schema::table('property_facilities', function (Blueprint $table) {
            $table->dropForeign(['real_estate_id']);
            $table->renameColumn('real_estate_id', 'property_id');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropForeign(['real_estate_id']);
            $table->foreign('real_estate_id')->references('id')->on('properties')->onDelete('cascade');
        });
    }
};
