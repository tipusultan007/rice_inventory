<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateCsv;
use App\Jobs\GenerateSaleProductCSV;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class SaleProductExportController extends Controller
{
    public function exportToCSV(Request $request)
    {
        $date1 = $request->input('date1');
        $date2 = $request->input('date2');
        $product_id = $request->input('product_id');

        $query = SaleDetail::with('sale.customer');

        if ($request->filled('date1') && $request->filled('date2')) {
            $query->whereHas('sale', function ($query) use ($date1, $date2) {
                $query->whereBetween('date', [$date1, $date2]);
            });
        }

        if ($product_id) {
            $query->where('product_id', $product_id);
        }

        $sales = $query->orderBy('sale_id', 'asc')->get();

        GenerateSaleProductCSV::dispatch($sales);

        return back()->with('message', 'CSV ফাইল তৈরি করা হচ্ছে। এটি ডাউনলোডের জন্য প্রস্তুত হলে আপনাকে জানানো হবে।');
    }

}
