<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    // Product model

    public function getProductDetails($productId, $startDate, $endDate)
    {
        // Retrieve product details
        $product = $this->with(['purchaseDetails', 'saleDetails', 'purchaseReturnDetails', 'saleReturnDetails'])
            ->find($productId);

        if ($product) {
            // Query purchase quantities within date range
            $purchaseQuantity = $product->purchaseDetails()
                ->whereHas('purchase', function($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->sum('quantity');

            // Query sale quantities within date range
            $saleQuantity = $product->saleDetails()
                ->whereHas('sale', function($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->sum('quantity');

            // Query purchase return quantities within date range
            $purchaseReturnQuantity = $product->purchaseReturnDetails()
                ->whereHas('purchaseReturn', function($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->sum('quantity');

            // Query sale return quantities within date range
            $saleReturnQuantity = $product->saleReturnDetails()
                ->whereHas('saleReturn', function($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->sum('quantity');

            // Return product details along with quantities
            return [
                'product_name' => $product->name, // Assuming 'name' is the attribute for product name
                'purchase_quantity' => $purchaseQuantity,
                'sale_quantity' => $saleQuantity,
                'purchase_return_quantity' => $purchaseReturnQuantity,
                'sale_return_quantity' => $saleReturnQuantity,
            ];
        } else {
            // If product not found, return empty array
            return [];
        }
    }

    public static function allProductsDetailsWithinDateRange($startDate, $endDate)
    {
        // Retrieve product details within date range
        return Product::with(['purchaseDetails', 'saleDetails', 'purchaseReturnDetails', 'saleReturnDetails'])
            ->whereHas('purchaseDetails', function($query) use ($startDate, $endDate) {
                $query->whereHas('purchase', function($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                });
            })
            ->orWhereHas('saleDetails', function($query) use ($startDate, $endDate) {
                $query->whereHas('sale', function($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                });
            })
            ->orWhereHas('purchaseReturnDetails', function($query) use ($startDate, $endDate) {
                $query->whereHas('purchaseReturn', function($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                });
            })
            ->orWhereHas('saleReturnDetails', function($query) use ($startDate, $endDate) {
                $query->whereHas('saleReturn', function($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                });
            })
            ->get()
            ->map(function ($product) use ($startDate, $endDate) {
                // Calculate quantities within date range
                $purchaseQuantity = $product->purchaseDetails()
                    ->whereHas('purchase', function($query) use ($startDate, $endDate) {
                        $query->whereBetween('date', [$startDate, $endDate]);
                    })
                    ->sum('quantity');

                $saleQuantity = $product->saleDetails()
                    ->whereHas('sale', function($query) use ($startDate, $endDate) {
                        $query->whereBetween('date', [$startDate, $endDate]);
                    })
                    ->sum('quantity');

                $purchaseReturnQuantity = $product->purchaseReturnDetails()
                    ->whereHas('purchaseReturn', function($query) use ($startDate, $endDate) {
                        $query->whereBetween('date', [$startDate, $endDate]);
                    })
                    ->sum('quantity');

                $saleReturnQuantity = $product->saleReturnDetails()
                    ->whereHas('saleReturn', function($query) use ($startDate, $endDate) {
                        $query->whereBetween('date', [$startDate, $endDate]);
                    })
                    ->sum('quantity');

                // Return product details along with quantities
                return [
                    'product_name' => $product->name,
                    'purchase_quantity' => $purchaseQuantity,
                    'sale_quantity' => $saleQuantity,
                    'purchase_return_quantity' => $purchaseReturnQuantity,
                    'sale_return_quantity' => $saleReturnQuantity,
                ];
            });
    }
    public function getProductDetailsByDateRange($startDate, $endDate)
    {
        return $this->with(['purchaseDetails', 'purchaseReturnDetails', 'saleDetails', 'saleReturnDetails'])
            ->with(['purchaseDetails' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('purchase', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                });
            }])
            ->with(['purchaseReturnDetails' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('purchaseReturn', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                });
            }])
            ->with(['saleDetails' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('sale', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                });
            }])
            ->with(['saleReturnDetails' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('saleReturn', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                });
            }])
            ->get();
    }

    public static function getAllProductsDetailsByDateRange($startDate, $endDate)
    {
        $query = "SELECT products.name AS product_name,
                         purchases.date AS purchase_date,
                         purchase_details.quantity AS purchase_quantity,
                         purchase_returns.date AS purchase_return_date,
                         purchase_return_details.quantity AS purchase_return_quantity,
                         sales.date AS sale_date,
                         sale_details.quantity AS sale_quantity,
                         sale_returns.date AS sale_return_date,
                         sale_return_details.quantity AS sale_return_quantity
                  FROM products
                  LEFT JOIN purchase_details ON products.id = purchase_details.product_id
                  LEFT JOIN purchases ON purchase_details.purchase_id = purchases.id
                  LEFT JOIN purchase_return_details ON products.id = purchase_return_details.product_id
                  LEFT JOIN purchase_returns ON purchase_return_details.purchase_return_id = purchase_returns.id
                  LEFT JOIN sale_details ON products.id = sale_details.product_id
                  LEFT JOIN sales ON sale_details.sale_id = sales.id
                  LEFT JOIN sale_return_details ON products.id = sale_return_details.product_id
                  LEFT JOIN sale_returns ON sale_return_details.sale_return_id = sale_returns.id
                  WHERE (purchases.date BETWEEN ? AND ? OR purchase_returns.date BETWEEN ? AND ? OR sales.date BETWEEN ? AND ? OR sale_returns.date BETWEEN ? AND ?)
                  ORDER BY product_name, purchase_date, sale_date, purchase_return_date, sale_return_date";

        // Executing the raw SQL query
        return DB::select($query, [$startDate, $endDate, $startDate, $endDate, $startDate, $endDate, $startDate, $endDate]);
    }
}
