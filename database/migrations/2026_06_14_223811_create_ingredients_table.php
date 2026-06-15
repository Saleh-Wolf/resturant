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
    Schema::create('ingredients', function (Blueprint $table) {
        $table->id();

        $table->string('name');

        $table->enum('unit', [
            'gram',
            'kg',
            'ml',
            'liter',
            'piece',
        ]);

        $table->decimal('current_stock', 12, 2)
            ->default(0);

        $table->decimal('minimum_stock', 12, 2)
            ->default(0);

        $table->decimal('cost_per_unit', 12, 2)
            ->default(0);

        $table->boolean('is_active')
            ->default(true);

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
