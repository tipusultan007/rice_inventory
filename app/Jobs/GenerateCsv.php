<?php

namespace App\Jobs;

use App\Models\SaleDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class GenerateCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function handle()
    {
        // Fetch sale details with related models within the date range
        $purchases = SaleDetail::with('product', 'sale.customer')->cursor();

        // Generate a unique filename for the CSV file based on the current date and time
        $filename = 'sale_products_' . now()->format('Y-m-d_H-i-s') . '.csv';

        // Create CSV file in the storage directory
        $csv = Writer::createFromString('');
        $csv->insertOne([
            'তারিখ',
            'ক্রেতা',
            'মেমো নং',
            'পণ্য',
            'পরিমাণ',
            'দর',
            'সর্বমোট',
        ]);

        // Insert data rows
        foreach ($purchases as $purchase) {
            $csv->insertOne([
                $purchase->sale->date,
                $purchase->sale->customer->name,
                $purchase->sale->invoice_no,
                $purchase->product->name,
                $purchase->quantity,
                $purchase->price_rate,
                $purchase->amount
            ]);
        }

        // Store the CSV file in the storage directory
        Storage::put('public/csv/' . $filename, $csv->getContent());
    }
}
