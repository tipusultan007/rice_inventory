<?php

namespace App\Jobs;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessSalesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Sale::where('id','>',763)->chunk(1000, function ($sales) {
            foreach ($sales as $sale) {
                foreach ($sale->saleDetails as $product) {
                    $productModel = Product::find($product['product_id']);
                    $productModel->quantity -= $product['quantity'];
                    $productModel->save();
                }

                Transaction::create([
                    'amount' => $sale->total,
                    'type' => 'debit',
                    'reference_id' => $sale->id,
                    'transaction_type' => 'sale',
                    'customer_id' => $sale->customer_id,
                    'date' => $sale->date,
                ]);

                $paid = $sale->paid;
                $remain = $sale->total - $paid;

                if ($paid > 0) {
                    Transaction::create([
                        'account_id' => 1,
                        'amount' => $paid,
                        'type' => 'credit',
                        'reference_id' => $sale->id,
                        'transaction_type' => 'customer_payment',
                        'customer_id' => $sale->customer_id,
                        'date' => $sale->date,
                    ]);
                }

                if ($remain > 0) {
                    Transaction::create([
                        'amount' => $remain,
                        'type' => 'credit',
                        'reference_id' => $sale->id,
                        'transaction_type' => 'sale',
                        'customer_id' => $sale->customer_id,
                        'date' => $sale->date,
                    ]);
                }
            }
        });
    }
}
