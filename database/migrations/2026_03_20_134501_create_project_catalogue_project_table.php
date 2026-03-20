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
        if (!Schema::hasTable('project_catalogue_project')) {
            Schema::create('project_catalogue_project', function (Blueprint $table) {
                $table->unsignedBigInteger('project_catalogue_id');
                $table->unsignedBigInteger('project_id');

                $table->foreign('project_catalogue_id', 'pcp_cat_id_foreign')->references('id')->on('project_catalogues')->onDelete('cascade');
                $table->foreign('project_id', 'pcp_proj_id_foreign')->references('id')->on('projects')->onDelete('cascade');
                $table->primary(['project_catalogue_id', 'project_id'], 'pcp_primary');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_catalogue_project');
    }
};
