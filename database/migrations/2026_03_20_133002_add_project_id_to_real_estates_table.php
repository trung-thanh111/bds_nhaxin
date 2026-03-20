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
        if (!Schema::hasColumn('real_estates', 'project_id')) {
            Schema::table('real_estates', function (Blueprint $table) {
                $table->unsignedBigInteger('project_id')->nullable()->after('real_estate_catalogue_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('real_estates', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });
    }
};
