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
            $table->integer('apartment_count')->default(0)->after('is_project');
            $table->integer('block_count')->default(0)->after('apartment_count');
            $table->string('area')->nullable()->after('block_count');
            $table->string('legal_status')->nullable()->after('area');
        });

        Schema::create('project_relation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('related_project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('related_project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_relation');
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['apartment_count', 'block_count', 'area', 'legal_status']);
        });
    }
};
