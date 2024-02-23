<?php

use App\Models\Purchase;
use App\Models\Sale;

function generatePurchaseInvoiceNumber()
{
    $lastPurchase = Purchase::latest()->first();

    $lastInvoiceNumber = $lastPurchase ? $lastPurchase->invoice_no : 0;

    // Increment the last invoice number
    $newInvoiceNumber = $lastInvoiceNumber + 1;

    return $newInvoiceNumber;
}

function generateSaleInvoiceNumber()
{
    $lastSale = Sale::latest()->first();

    $lastInvoiceNumber = $lastSale ? $lastSale->invoice_no : 0;

    // Increment the last invoice number
    $newInvoiceNumber = $lastInvoiceNumber + 1;

    return $newInvoiceNumber;
}
