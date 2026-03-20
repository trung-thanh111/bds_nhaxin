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
        Schema::table('project_catalogues', function (Blueprint $table) {
            if (!Schema::hasColumn('project_catalogues', 'image')) {
                $table->string('image')->nullable()->after('icon_url');
            }
            if (!Schema::hasColumn('project_catalogues', 'album')) {
                $table->text('album')->nullable()->after('image');
            }
            if (!Schema::hasColumn('project_catalogues', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('publish');
            }
            if (!Schema::hasColumn('project_catalogues', 'follow')) {
                $table->tinyInteger('follow')->default(2)->after('publish');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_catalogues', function (Blueprint $table) {
            $table->dropColumn(['image', 'album', 'user_id', 'follow']);
        });
    }
};
