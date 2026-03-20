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
        if (!Schema::hasTable('project_catalogue_language')) {
            Schema::create('project_catalogue_language', function (Blueprint $table) {
                $table->unsignedBigInteger('project_catalogue_id');
                $table->unsignedBigInteger('language_id');
                $table->string('name');
                $table->text('description')->nullable();
                $table->longText('content')->nullable();
                $table->string('canonical')->unique();
                $table->string('meta_title')->nullable();
                $table->string('meta_keyword')->nullable();
                $table->text('meta_description')->nullable();
                $table->timestamps();

                $table->foreign('project_catalogue_id', 'pc_lang_id_foreign')->references('id')->on('project_catalogues')->onDelete('cascade');
                $table->foreign('language_id', 'pc_lang_lang_id_foreign')->references('id')->on('languages')->onDelete('cascade');
                $table->primary(['project_catalogue_id', 'language_id'], 'pc_lang_primary');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_catalogue_language');
    }
};
