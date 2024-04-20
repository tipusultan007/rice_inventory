<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 *
 * @property $id
 * @property $name
 * @property $type
 * @property $quantity
 * @property $quantity_alt
 * @property $price_rate
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Product extends Model
{

    static $rules = [
		'name' => 'required',
		'type' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name','type','quantity','quantity_alt','price_rate','initial_stock'];

    public function getStockForDate($date) {
        $initialStock = $this->initial_stock;
        $totalSales = $this->sales()
            ->whereHas('sale', function ($query) use ($date) {
                $query->whereDate('date', '<=', $date);
            })
            ->sum('quantity');

        $totalPurchases = $this->purchases()
            ->whereHas('purchase', function ($query) use ($date) {
                $query->whereDate('date', '<=', $date);
            })
            ->sum('quantity');

        $totalSaleReturns = $this->saleReturns()
            ->whereHas('saleReturn', function ($query) use ($date) {
                $query->whereDate('date', '<=', $date);
            })
            ->sum('quantity');

        $totalPurchaseReturns = $this->purchaseReturns()
            ->whereHas('purchaseReturn', function ($query) use ($date) {
                $query->whereDate('date', '<=', $date);
            })
            ->sum('quantity');

        $currentStock = $initialStock + $totalPurchases - $totalSales + $totalPurchaseReturns - $totalSaleReturns;

        return $currentStock < 0 ? 0 : $currentStock;
    }
    public function purchases()
    {
        return $this->hasMany(PurchaseDetail::class);
    }
    public function purchaseReturns()
    {
        return $this->hasMany(PurchaseReturnDetail::class);
    }

    public function sales()
    {
        return $this->hasMany(SaleDetail::class);
    }
    public function saleReturns()
    {
        return $this->hasMany(SaleReturnDetail::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function purchaseReturnDetails()
    {
        return $this->hasMany(PurchaseReturnDetail::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function saleReturnDetails()
    {
        return $this->hasMany(SaleReturnDetail::class);
    }

   /* public function getTotalStockAndValue($date) {
        // Step 1: Calculate total stock for each product
        $products = Product::with(['purchaseDetails.purchase', 'purchaseReturnDetails.purchaseReturn', 'saleDetails.sale', 'saleReturnDetails.saleReturn'])
            ->get()
            ->map(function ($product) use ($date) {
                // Calculate total stock
                $totalSales = $product->saleDetails->sum(function ($saleDetail) use ($date) {
                    return $saleDetail->sale->date <= $date ? $saleDetail->quantity : 0;
                });
                $totalSalesReturns = $product->saleReturnDetails->sum(function ($saleReturnDetail) use ($date) {
                    return $saleReturnDetail->saleReturn->date <= $date ? $saleReturnDetail->quantity : 0;
                });
                $totalPurchases = $product->purchaseDetails->sum(function ($purchaseDetail) use ($date) {
                    return $purchaseDetail->purchase->date <= $date ? $purchaseDetail->quantity : 0;
                });
                $totalPurchaseReturns = $product->purchaseReturnDetails->sum(function ($purchaseReturnDetail) use ($date) {
                    return $purchaseReturnDetail->purchaseReturn->date <= $date ? $purchaseReturnDetail->quantity : 0;
                });

                $currentStock = $product->initial_stock + $totalPurchases - $totalSales + $totalPurchaseReturns - $totalSalesReturns;

                // Step 2: Retrieve the latest purchase rate for each product
                $latestPurchase = $product->purchaseDetails->where('purchase.date', '<=', $date)->sortByDesc('purchase.date')->first();
                $latestPurchaseRate = $latestPurchase ? $latestPurchase->price_rate : 0;

                // Step 3: Calculate the total value
                $totalValue = $currentStock * $latestPurchaseRate;

                return [
                    'product' => $product,
                    'current_stock' => $currentStock,
                    'latest_purchase_rate' => $latestPurchaseRate,
                    'total_value' => $totalValue
                ];
            });

        // Step 4: Sum up total stock and total value for all products
        $totalStock = $products->sum('current_stock');
        $totalValue = $products->sum('total_value');

        return [
            'total_products' => $products->count(),
            'total_value' => $totalValue,
            'total_stock' => $totalStock,
            'products' => $products
        ];
    }*/

    public function getTotalStockAndValue($date) {
        // Step 1: Calculate total stock for each product
        $products = Product::with(['purchaseDetails.purchase', 'purchaseReturnDetails.purchaseReturn', 'saleDetails.sale', 'saleReturnDetails.saleReturn'])
            ->get()
            ->map(function ($product) use ($date) {
                // Calculate total stock
                $totalSales = $product->saleDetails->sum(function ($saleDetail) use ($date) {
                    return $saleDetail->sale->date <= $date ? $saleDetail->quantity : 0;
                });
                $totalSalesReturns = $product->saleReturnDetails->sum(function ($saleReturnDetail) use ($date) {
                    return $saleReturnDetail->saleReturn->date <= $date ? $saleReturnDetail->quantity : 0;
                });
                $totalPurchases = $product->purchaseDetails->sum(function ($purchaseDetail) use ($date) {
                    return $purchaseDetail->purchase->date <= $date ? $purchaseDetail->quantity : 0;
                });
                $totalPurchaseReturns = $product->purchaseReturnDetails->sum(function ($purchaseReturnDetail) use ($date) {
                    return $purchaseReturnDetail->purchaseReturn->date <= $date ? $purchaseReturnDetail->quantity : 0;
                });

                $currentStock = $product->initial_stock + $totalPurchases - $totalSales + $totalPurchaseReturns - $totalSalesReturns;

                // Step 2: Retrieve the latest purchase rate for each product
                $latestPurchase = $product->purchaseDetails->where('purchase.date', '<=', $date)->sortByDesc('purchase.date')->first();
                $latestPurchaseRate = $latestPurchase ? $latestPurchase->price_rate : $product->price_rate;

                // Step 3: Calculate the total value
                $totalValue = $currentStock * $latestPurchaseRate;

                return [
                    'product' => $product,
                    'current_stock' => $currentStock,
                    'latest_purchase_rate' => $latestPurchaseRate,
                    'total_value' => $totalValue
                ];
            });

        // Step 4: Sum up total stock and total value for all products
        $totalStock = $products->sum('current_stock');
        $totalValue = $products->sum('total_value');

        return [
            'total_products' => $products->count(),
            'total_value' => $totalValue,
            'total_stock' => $totalStock,
            'products' => $products
        ];
    }
}
