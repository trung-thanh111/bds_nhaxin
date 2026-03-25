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
            $table->dropForeign(['agent_id']);
            $table->dropColumn([
                'agent_id',
                'transaction_type',
                'price',
                'price_unit',
                'price_vnd',
                'price_negotiable',
                'type_code'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')->nullable()->after('project_catalogue_id');
            $table->string('transaction_type')->nullable()->after('agent_id');
            $table->double('price')->default(0)->after('transaction_type');
            $table->integer('price_unit')->default(0)->after('price');
            $table->double('price_vnd')->default(0)->after('price_unit');
            $table->tinyInteger('price_negotiable')->default(0)->after('price_vnd');
            $table->string('type_code')->nullable()->after('price_negotiable');
            
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('set null');
        });
    }
};
