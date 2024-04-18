<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 *
 * @property $id
 * @property $customer_id
 * @property $supplier_id
 * @property $sale_id
 * @property $purchase_id
 * @property $sale_return_id
 * @property $purchase_return_id
 * @property $amount
 * @property $balance
 * @property $date
 * @property $trx_id
 * @property $user_id
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Payment extends Model
{
    
    static $rules = [
		'amount' => 'required',
		'balance' => 'required',
		'date' => 'required',
		'trx_id' => 'required',
		'user_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['customer_id','supplier_id','sale_id','purchase_id','sale_return_id','purchase_return_id','amount','balance','date','trx_id','user_id'];



}
