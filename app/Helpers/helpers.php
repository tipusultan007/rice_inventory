<?php

use App\Models\Purchase;
use App\Models\Sale;

function generatePurchaseInvoiceNumber()
{
    $lastPurchase = Purchase::where('user_id',auth()->id())->latest()->first();

    $lastInvoiceNumber = $lastPurchase ? $lastPurchase->invoice_no : 0;

    // Increment the last invoice number
    $newInvoiceNumber = $lastInvoiceNumber + 1;

    return $newInvoiceNumber;
}

function generateSaleInvoiceNumber()
{
    $lastSale = Sale::where('user_id',auth()->id())->latest()->first();

    $lastInvoiceNumber = $lastSale ? $lastSale->invoice_no : 0;

    // Increment the last invoice number
    $newInvoiceNumber = $lastInvoiceNumber + 1;

    return $newInvoiceNumber;
}

function lastBookNo()
{
    $lastSale = Sale::where('user_id',auth()->id())->latest()->first();

    return $lastSale?$lastSale->book_no:"1";
}
