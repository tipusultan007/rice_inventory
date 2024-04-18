<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Jobs\GeneratePurchaseCSV;
use App\Models\Purchase;
use Illuminate\Http\Request;
use League\Csv\CharsetConverter;
use League\Csv\Writer;

class PurchaseExportController extends Controller
{
    public function exportToCSV(Request $request)
    {
        $date1 = $request->input('date1');
        $date2 = $request->input('date2');
        if (request()->has('date1') && request()->has('date2')) {
            $purchases = Purchase::with('supplier','purchaseDetails')
                ->whereBetween('date', [$date1, $date2])
                ->orderBy('date','asc')
                ->get();
        }else{
            $purchases = Purchase::with('supplier','purchaseDetails')->get();
        }

        GeneratePurchaseCSV::dispatch($purchases);

        return back()->with('message', 'CSV ফাইল তৈরি করা হচ্ছে। এটি ডাউনলোডের জন্য প্রস্তুত হলে আপনাকে জানানো হবে।');
    }
}
