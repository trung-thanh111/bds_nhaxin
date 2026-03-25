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
            $table->decimal('price_sale', 20, 2)->nullable()->after('usable_area');
            $table->decimal('price_rent', 20, 2)->nullable()->after('price_sale');
            $table->string('price_unit')->nullable()->after('price_rent'); // ID của thuộc tính loai_gia
            $table->string('transaction_type')->nullable()->after('price_unit'); // ID của thuộc tính loai_giao_dich
            $table->text('album')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('real_estates', function (Blueprint $table) {
            $table->dropColumn([
                'price_sale',
                'price_rent',
                'price_unit',
                'transaction_type',
                'album'
            ]);
        });
    }
};
