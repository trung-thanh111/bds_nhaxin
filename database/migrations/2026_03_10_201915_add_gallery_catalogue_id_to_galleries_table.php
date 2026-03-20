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
        Schema::table('galleries', function (Blueprint $table) {
            if (!Schema::hasColumn('galleries', 'gallery_catalogue_id')) {
                $table->unsignedBigInteger('gallery_catalogue_id')->nullable()->after('property_id');
            }
            $table->foreign('gallery_catalogue_id')->references('id')->on('gallery_catalogues')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropForeign(['gallery_catalogue_id']);
            $table->dropColumn('gallery_catalogue_id');
        });
    }
};
