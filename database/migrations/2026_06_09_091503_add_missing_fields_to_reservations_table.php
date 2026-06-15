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
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('reservation_number')
                ->nullable()
                ->unique();

            $table->enum('reservation_type', [
                'immediate',
                'scheduled'
            ])->default('immediate');

            $table->time('reservation_time')
                ->nullable();

            $table->decimal('estimated_duration', 3, 1)
                ->nullable();

            $table->string('special_occasion')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            //
        });
    }
};
