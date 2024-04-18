<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PurchaseDetail
 *
 * @property $id
 * @property $purchase_id
 * @property $product_id
 * @property $weight
 * @property $quantity
 * @property $amount
 * @property $price_rate
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class PurchaseDetail extends Model
{

    static $rules = [
		'purchase_id' => 'required',
		'product_id' => 'required',
		'quantity' => 'required',
		'amount' => 'required',
		'price_rate' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['purchase_id','product_id','weight','quantity','amount','price_rate'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
