<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Sale;
use App\Models\SaleReturn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalePurchaseChartController extends Controller
{
    public function getDailySalePurchaseChartData()
    {
        // Get the start and end dates of the current month
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // Generate an array of dates for the current month
        $dates = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        // Query data for each transaction type for each date
        $salesData = $this->getDailyTransactionData(new Sale, $dates);
        $purchaseData = $this->getDailyTransactionData(new Purchase, $dates);
        $saleReturnsData = $this->getDailyTransactionData(new SaleReturn, $dates);
        $purchaseReturnsData = $this->getDailyTransactionData(new PurchaseReturn, $dates);


        // Combine data into the format suitable for the chart
        $chartData = [
            'series' => [
                [
                    'name' => 'Sales',
                    'data' => $salesData,
                ],
                [
                    'name' => 'Purchases',
                    'data' => $purchaseData,
                ],
                [
                    'name' => 'Sale Returns',
                    'data' => $saleReturnsData,
                ],
                [
                    'name' => 'Purchase Returns',
                    'data' => $purchaseReturnsData,
                ],
            ],
            'xaxis' => [
                'categories' => $dates,
            ],
        ];

        return response()->json($chartData);
    }

    private function getDailyTransactionData($model, $dates)
    {
        $queryResults = $model::select('date', DB::raw('SUM(total) as total'))
            ->whereIn('date', $dates)
            ->groupBy('date')
            ->get();

        $data = [];
        foreach ($dates as $date) {
            $found = false;
            foreach ($queryResults as $result) {
                if ($result->date == $date) {
                    $data[] = $result->total;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $data[] = 0;
            }
        }

        return $data;
    }

    private function getChartData($model, $startDate, $endDate)
    {
        $data = $model::whereBetween('date', [$startDate, $endDate])
            ->select(DB::raw('SUM(total) AS total'), 'date')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total')
            ->toArray();

        // Fill missing dates with 0
        $datesInRange = $startDate->toPeriod($endDate)->toArray();
        $formattedData = [];
        foreach ($datesInRange as $date) {
            $formattedData[$date->format('Y-m-d')] = 0;
        }
        foreach ($data as $item) {
            $formattedData[$item['date']] = $item['total'];
        }

        return array_values($formattedData);

    }
    private function getDateCategories($startDate, $endDate)
    {
        $dates = $startDate->toPeriod($endDate)->toArray();
        return array_map(function ($date) {
            return $date->format('d M');
        }, $dates);
    }
}
