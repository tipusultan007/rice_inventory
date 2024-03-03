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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('loan_id')->nullable();

            $table->decimal('amount', 10, 2);
            $table->enum('type', ['debit', 'credit']); // Common types: debit and credit
            $table->unsignedBigInteger('reference_id')->nullable(); // Reference to sale, purchase, or due record
            $table->enum('transaction_type', [
                'balance_transfer_out',
                'balance_transfer_in',
                'external_payment_received',
                'external_payment_made',
                'due_payment',
                'supplier_payment',
                'sale',
                'purchase',
                'loan_taken',
                'loan_repayment',
                'loan_interest',
                'asset',
            ])->nullable();
            $table->text('note')->nullable();
            $table->string('cheque_no')->nullable();
            $table->text('cheque_details')->nullable();
            $table->date('date');
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
