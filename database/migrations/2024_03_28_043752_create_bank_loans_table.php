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
        Schema::create('bank_loans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('loan_amount');
            $table->decimal('interest', 10, 2);
            $table->decimal('duration', 10, 2);
            $table->integer('total_loan')->nullable();
            $table->integer('grace')->nullable();
            $table->string('trx_id');
            $table->text('description')->nullable();
            $table->integer('initial_balance')->nullable();
            $table->date('date');
            $table->date('expire')->nullable();
            $table->date('balance_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_loans');
    }
};
