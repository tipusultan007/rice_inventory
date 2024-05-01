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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('loan_amount');
            $table->integer('initial_balance')->nullable();
            $table->decimal('interest_rate', 10, 2);
            $table->integer('grace')->nullable();
            $table->string('trx_id');
            $table->text('description')->nullable();
            $table->date('date');
            $table->date('balance_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
