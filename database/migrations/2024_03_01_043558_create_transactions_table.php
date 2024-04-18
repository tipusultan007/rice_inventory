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
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('account_name')->nullable();
            $table->string('trx_id')->nullable();

            $table->decimal('amount', 10, 2);
            $table->integer('balance')->nullable();
            $table->enum('type', ['debit', 'credit']);
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->enum('transaction_type', [
                'balance_transfer',
                'customer_due',
                'customer_payment',
                'supplier_due',
                'supplier_opening_balance',
                'customer_opening_balance',
                'account_opening_balance',
                'supplier_payment',
                'sale',
                'sale_return',
                'purchase',
                'purchase_return',
                'loan_taken',
                'loan_repayment',
                'loan_interest',
                'capital',
                'capital_withdraw',
                'capital_profit',
                'asset',
                'income',
                'expense',
                'tohori',
                'supplier',
                'discount',
                'bank_loan',
                'bank_loan_repayment',
                'investment',
                'investment_repayment',
                'salary',
                'tohori_fund',
                'rebate',
                'due_from_supplier',
                'payment_from_supplier',
                'payment_to_customer',
                'due_to_customer',
                'asset_sell',
                'profit_from_asset',
                'loss_at_asset',
            ])->nullable();
            $table->text('note')->nullable();
            $table->string('cheque_no')->nullable();
            $table->text('cheque_details')->nullable();
            $table->date('date');
            $table->timestamps();
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
