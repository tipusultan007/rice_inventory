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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('carrying_cost', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('tohori', 10, 2)->nullable();
            $table->decimal('total', 10, 2);
            $table->string('note')->nullable();
            $table->string('truck_no')->nullable();
            $table->string('invoice_no')->nullable();
            $table->integer('due')->nullable();
            $table->string('attachment')->nullable();
            $table->integer('paid')->nullable();
            $table->string('trx_id');
            $table->timestamps();

        });

        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('weight')->nullable();
            $table->integer('quantity');
            $table->decimal('amount', 10, 2);
            $table->decimal('price_rate', 10, 2); // Added column
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
        Schema::dropIfExists('purchases');
    }
};
