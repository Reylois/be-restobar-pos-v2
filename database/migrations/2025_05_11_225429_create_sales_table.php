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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('subtotal', 8, 2)->default(0);
            $table->unsignedTinyInteger('discount')->default(0);
            $table->decimal('total_amount',8,2)->default(0);
            $table->enum('order_type', ['dine-in', 'take-out'])->default('dine-in');
            $table->enum('payment_method', ['cash', 'gcash'])->default('cash');
            $table->decimal('amount_paid')->default(0);
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
