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
        Schema::table('contact_requests', function (Blueprint $table) {
            if (Schema::hasColumn('contact_requests', 'property_id')) {
                $table->dropForeign(['property_id']);
                $table->dropColumn('property_id');
            }

            if (Schema::hasColumn('contact_requests', 'preferred_date')) {
                $table->dropColumn('preferred_date');
            }

            if (Schema::hasColumn('contact_requests', 'preferred_time')) {
                $table->dropColumn('preferred_time');
            }

            if (!Schema::hasColumn('contact_requests', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable()->after('id')->comment('Dự án quan tâm');
            }

            if (!Schema::hasColumn('contact_requests', 'subject')) {
                $table->string('subject', 255)->nullable()->after('phone')->comment('Tiêu đề yêu cầu');
            }

            // Handle content/message renaming or adding
            if (Schema::hasColumn('contact_requests', 'message')) {
                if (!Schema::hasColumn('contact_requests', 'content')) {
                    $table->renameColumn('message', 'content');
                } else {
                    $table->dropColumn('message');
                }
            } elseif (!Schema::hasColumn('contact_requests', 'content')) {
                $table->text('content')->nullable()->after('subject')->comment('Nội dung yêu cầu');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_requests', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn(['project_id', 'subject']);
            if (Schema::hasColumn('contact_requests', 'content')) {
                $table->renameColumn('content', 'message');
            }
            $table->unsignedBigInteger('property_id')->nullable()->after('id');
            $table->date('preferred_date')->nullable();
            $table->time('preferred_time')->nullable();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });
    }
};
