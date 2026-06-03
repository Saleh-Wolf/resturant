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
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->string('type')->default('public')->after('table_number');
            $table->integer('min_capacity')->default(1)->after('type');
            $table->integer('max_capacity')->default(1)->after('min_capacity');
            $table->string('location')->nullable()->after('max_capacity');
            $table->text('notes')->nullable()->after('status');
            $table->string('qr_token')->nullable()->unique()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'min_capacity',
                'max_capacity',
                'location',
                'notes',
                'qr_token',
            ]);
        });
    }
};
