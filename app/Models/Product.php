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
}
