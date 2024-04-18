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
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('total', 10, 2);
            $table->integer('paid')->nullable();
            $table->string('note')->nullable();
            $table->string('attachment')->nullable();
            $table->string('trx_id');
            $table->timestamps();

        });

        Schema::create('purchase_return_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_return_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('weight')->nullable(); // Adjust if needed
            $table->integer('quantity');
            $table->decimal('amount', 10, 2);
            $table->decimal('price_rate', 10, 2); // Adjust if needed
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
        Schema::dropIfExists('purchase_return_details');
    }
};
