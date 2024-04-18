<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateSalesCSV;
use App\Models\Sale;
use Illuminate\Http\Request;
use League\Csv\Writer;

class SaleExportController extends Controller
{
    public function exportToCSV(Request $request)
    {
        $date1 = $request->input('date1');
        $date2 = $request->input('date2');
        if (request()->has('date1') && request()->has('date2')) {
            $sales = Sale::with('customer','saleDetails')
                ->whereBetween('date', [$date1, $date2])
                ->orderBy('date','asc')
                ->get();
        }else{
            $sales = Sale::with('customer','saleDetails')->get();
        }

        GenerateSalesCSV::dispatch($sales);

        return back()->with('message', 'CSV ফাইল তৈরি করা হচ্ছে। এটি ডাউনলোডের জন্য প্রস্তুত হলে আপনাকে জানানো হবে।');
    }
}
