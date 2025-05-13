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
        Schema::table('orders', function (Illuminate\Database\Schema\Blueprint $table) {
        $table->decimal('total_price', 15, 2)->after('quantity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn('total_price');
        });
    }
};
