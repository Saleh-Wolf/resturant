<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->enum('order_type', [
            'dine_in',
            'takeaway',
        ])
            ->default('dine_in')
            ->after('reservation_id');

        $table->dropForeign(['restaurant_table_id']);

        $table->foreignId('restaurant_table_id')
            ->nullable()
            ->change();
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('order_type');
    });
}
};
