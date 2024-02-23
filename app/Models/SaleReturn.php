<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SaleReturn
 *
 * @property $id
 * @property $date
 * @property $customer_id
 * @property $user_id
 * @property $invoice_no
 * @property $subtotal
 * @property $dholai
 * @property $discount
 * @property $total
 * @property $note
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class SaleReturn extends Model
{
    
    static $rules = [
		'date' => 'required',
		'customer_id' => 'required',
		'user_id' => 'required',
		'subtotal' => 'required',
		'total' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['date','customer_id','user_id','invoice_no','subtotal','dholai','discount','total','note'];



}
