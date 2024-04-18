<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Jobs\GeneratePurchaseProductCSV;
use App\Models\PurchaseDetail;
use Illuminate\Http\Request;
use League\Csv\Writer;

class PurchaseProductExportController extends Controller
{
    public function exportToCSV(Request $request)
    {
        $date1 = $request->input('date1');
        $date2 = $request->input('date2');
        $product_id = $request->input('product_id');

        $query = PurchaseDetail::with('purchase.customer');

        if ($request->filled('date1') && $request->filled('date2')) {
            $query->whereHas('purchase', function ($query) use ($date1, $date2) {
                $query->whereBetween('date', [$date1, $date2]);
            });
        }

        if ($product_id) {
            $query->where('product_id', $product_id);
        }

        $purchases = $query->orderBy('purchase_id', 'asc')->get();

        GeneratePurchaseProductCSV::dispatch($purchases);

        return back()->with('message', 'CSV ফাইল তৈরি করা হচ্ছে। এটি ডাউনলোডের জন্য প্রস্তুত হলে আপনাকে জানানো হবে।');
    }
}
