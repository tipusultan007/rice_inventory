<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Sale;
use App\Models\SaleReturn;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function incomeExpense()
    {
        $currentYear = now()->year;
        $monthlyIncome = Income::whereYear('date', $currentYear)
            ->selectRaw('MONTH(date) as month, sum(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyExpense = Expense::whereYear('date', $currentYear)
            ->selectRaw('MONTH(date) as month, sum(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        return response()->json([
            'monthlyIncome' => $monthlyIncome,
            'monthlyExpense' => $monthlyExpense,
        ]);
    }

    public function salesPurchases()
    {
        $currentYear = Carbon::now()->year;

        // Fetch sales data for the current year grouped by month
        $sales = Sale::whereYear('date', $currentYear)
            ->selectRaw('MONTH(date) as month, SUM(total) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fetch purchase data for the current year grouped by month
        $purchases = Purchase::whereYear('date', $currentYear)
            ->selectRaw('MONTH(date) as month, SUM(total) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        return response()->json([
            'sales' => $sales,
            'purchases' => $purchases,
        ]);
    }

    public function getDailySalesAndPurchaseData()
    {
        // Get current month and year
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');

        $daysInMonth = Carbon::now()->daysInMonth;

        // Initialize an array to hold the dates of the current month
        $dates = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($currentYear, $currentMonth, $day)->format('Y-m-d');
            $dates[$date] = 0; // Initialize sales, sales return, purchases, and purchase return to 0
        }

        // Fetch daily sales data for the current month
        $sales = Sale::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Fetch daily sales return data for the current month
        $salesReturn = SaleReturn::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Fetch daily purchase data for the current month
        $purchases = Purchase::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Fetch daily purchase return data for the current month
        $purchaseReturn = PurchaseReturn::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Merge all data arrays
        $data = [
            'dates' => $dates,
            'sales' => $sales,
            'sales_return' => $salesReturn,
            'purchases' => $purchases,
            'purchase_return' => $purchaseReturn,
        ];

        return response()->json($data);
    }
}
