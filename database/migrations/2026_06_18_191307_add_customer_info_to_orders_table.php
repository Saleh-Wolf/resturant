<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('customer_name')
            ->nullable()
            ->after('order_type');

        $table->string('customer_phone', 20)
            ->nullable()
            ->after('customer_name');

        $table->integer('guest_count')
            ->nullable()
            ->after('customer_phone');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn([
            'customer_name',
            'customer_phone',
            'guest_count',
        ]);
    });
}
};
