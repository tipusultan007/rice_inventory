<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SaleDetail
 *
 * @property $id
 * @property $sale_id
 * @property $product_id
 * @property $quantity
 * @property $amount
 * @property $price_rate
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class SaleDetail extends Model
{

    static $rules = [
		'sale_id' => 'required',
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
    protected $fillable = ['sale_id','product_id','quantity','amount','price_rate'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
