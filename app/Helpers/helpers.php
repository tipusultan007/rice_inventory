<?php

use App\Models\Purchase;
use App\Models\Sale;

if (!function_exists('generatePurchaseInvoiceNo')) {
    function generatePurchaseInvoiceNo()
    {
        $lastInvoiceNumber = getLastPurchaseInvoiceNumber();

        // Extract the date part from the last invoice number
        $lastDatePart = substr($lastInvoiceNumber, 4, 6);

        // Extract the last part (increment part) from the last invoice number
        $lastIncrementPart = intval(substr($lastInvoiceNumber, -6));

        // Get the current date
        $currentDate = date('ymd');

        // If the last used invoice number is of today's date, increment the last part

        $newIncrementPart = str_pad($lastIncrementPart + 1, 6, '0', STR_PAD_LEFT);

        // Generate the new invoice number
        $newInvoiceNumber = 'PUR-' . $currentDate . '-' . $newIncrementPart;

        return $newInvoiceNumber;
    }
}

if (!function_exists('getLastPurchaseInvoiceNumber')) {
    function getLastPurchaseInvoiceNumber()
    {
        // Retrieve the last purchase invoice number from the database
        $lastPurchase = Purchase::latest()->first();

        // If a purchase with an invoice number exists, return its invoice number
        if ($lastPurchase) {
            return $lastPurchase->invoice_no;
        }

        // If no purchase with an invoice number exists, return a default value
        // You can adjust this behavior based on your requirements
        return 'PUR-000000-000000';
    }
}

function generateSaleInvoiceNumber()
{
    $lastSale = Sale::where('user_id', auth()->id())->latest()->first();

    $lastInvoiceNumber = $lastSale ? $lastSale->invoice_no : 0;

    // Increment the last invoice number
    $newInvoiceNumber = $lastInvoiceNumber + 1;

    return $newInvoiceNumber;
}

function lastBookNo()
{
    $lastSale = Sale::where('user_id', auth()->id())->latest()->first();

    return $lastSale ? $lastSale->book_no : "1";
}

function transactionType($type)
{
    switch ($type) {
        case 'balance_transfer':
            return __('ব্যালেন্স ট্রান্সফার');
            break;

        case 'customer_payment':
            return __('ক্রেতার পেমেন্ট');
            break;

        case 'supplier_payment':
            return __('সরবরাহকারী পেমেন্ট');
            break;
        case 'supplier_due':
            return __('সরবরাহকারীর বকেয়া');
            break;
        case 'supplier_opening_balance':
            return __('সরবরাহকারীর শুরুর ব্যালেন্স');
            break;
        case 'customer_opening_balance':
            return __('ক্রেতার শুরুর ব্যালেন্স');
            break;
        case 'account_opening_balance':
            return __('অ্যাকাউন্ট এর শুরুর ব্যালেন্স');
            break;
        case 'customer_due':
            return __('ক্রেতার বকেয়া');
            break;
        case 'sale':
            return __('বিক্রয়');
            break;
        case 'salary':
            return __('বেতন');
            break;
        case 'sale_return':
            return __('বিক্রয় ফেরত');
            break;
        case 'purchase_return':
            return __('ক্রয় ফেরত');
            break;

        case 'purchase':
            return __('ক্রয়');
            break;

        case 'loan_taken':
            return __('লোন সংগ্রহ');
            break;
        case 'loan_repayment':
            return __('লোন পেমেন্ট');
            break;
        case 'loan_interest':
            return __('লোন কমিশন');
            break;
        case 'capital':
            return __('মূলধন');
            break;
        case 'capital_withdraw':
            return __('মূলধন উত্তোলন');
            break;
        case 'capital_profit':
            return __('মুনাফা উত্তোলন');
            break;
        case 'asset':
            return __('সম্পদ');
            break;
        case 'income':
            return __('আয়');
            break;
        case 'expense':
            return __('ব্যয়');
            break;
        case 'rebate':
            return __('Rebate');
            break;
        case 'tohori':
            return __('তহরি');
            break;
        case 'tohori_fund':
            return __('তহরি তহবিল');
            break;
        case 'supplier':
            return __('সরবরাহকারীর নাম');
            break;
        case 'discount':
            return __('ডিস্কাউন্ট');
            break;
        case 'bank_loan':
            return __('ব্যাংক ঋণ');
            break;
        case 'bank_loan_repayment':
            return __('ব্যাংক ঋণ পেমেন্ট');
            break;
        case 'investment':
            return __('বিনিয়োগ');
            break;
        case 'investment_repayment':
            return __('বিনিয়োগ পেমেন্ট');
            break;
        case 'asset_sell':
            return __('সম্পদ বিক্রয়');
            break;
        case 'profit_from_asset':
            return __('সম্পদ হতে আয়');
            break;
        case 'loss_at_asset':
            return __('সম্পদ হতে ক্ষতি');
            break;
        default:
            return '-';
    }
}

function getTransactionAccount($transaction)
{
    switch ($transaction->transaction_type) {
        case 'sale':
            return ($transaction->type === 'debit') ? $transaction->customer->name : '';
        case 'sale_return':
            if ($transaction->type === 'credit') {
                return $transaction->customer->name;
            } else {
                if ($transaction->account_id) {
                    return $transaction->account->name;
                } else {
                    return $transaction->customer->name;
                }
            }
        case 'purchase_return':
            if ($transaction->type === 'debit') {
                return $transaction->supplier->name;
            } else {
                if ($transaction->account_id) {
                    return $transaction->account->name;
                } else {
                    //return $transaction->supplier->name;
                    return '';
                }
            }
        case 'customer_payment':
            return ($transaction->type === 'debit') ? $transaction->customer->name : $transaction->account->name;
        case 'supplier_payment':
            return ($transaction->type === 'credit') ? $transaction->supplier->name : $transaction->account->name;
        case 'purchase':
            return ($transaction->type === 'credit') ? $transaction->supplier->name : '';
        case 'loan_taken':
            return ($transaction->type === 'credit') ? $transaction->account->name : $transaction->loan->name;
        case 'capital':
            return ($transaction->type === 'credit') ? $transaction->account->name : $transaction->capital->description;
        case 'asset':
            return ($transaction->type === 'debit') ? $transaction->account->name : $transaction->asset->name;
        case 'balance_transfer':
            return $transaction->account->name;
        case 'expense':
            if ($transaction->type === 'credit') {
                return $transaction->expenseCategory->name;
            } else {
                return $transaction->account->name;
            }
        case 'income':
            if ($transaction->type === 'credit') {
                return $transaction->account->name;
            } else {
                return $transaction->incomeCategory->name;
            }
        case 'loan_repayment':
        case 'loan_interest':
            return ($transaction->type === 'debit') ? $transaction->account->name : $transaction->loan->name;
        case 'capital_withdraw':
        case 'capital_profit':
            return ($transaction->type === 'debit') ? $transaction->account->name : $transaction->capital->description;
        default:
            return '';
    }
}

function calculateBalance($transactions)
{
    $balance = 0;

    foreach ($transactions as $transaction) {
        $amount = $transaction->amount * ($transaction->type === 'debit' ? 1 : -1);
        $balance += $amount;
    }

    return $balance;
}
