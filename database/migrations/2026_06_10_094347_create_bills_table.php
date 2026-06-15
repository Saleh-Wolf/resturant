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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();

            $table->string('bill_number')->unique();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('cashier_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount_total', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('service_charge', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);

            $table->enum('payment_method', [
                'cash',
                'card',
                'upi',
                'other',
            ])->default('cash');

            $table->decimal('amount_received', 10, 2)->nullable();
            $table->decimal('change_amount', 10, 2)->default(0);

            $table->enum('payment_status', [
                'pending',
                'paid',
                'partially_paid',
                'cancelled',
            ])->default('paid');

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
