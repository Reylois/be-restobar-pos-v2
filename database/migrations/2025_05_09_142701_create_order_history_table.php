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
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('restrict');
            $table->foreignId('product_list_id')->constrained()->onDelete('restrict');   
            $table->decimal('subtotal', 10, 2);
            $table->string('payment_method');
            $table->decimal('amount_paid', 10,  2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_history');
    }
};
