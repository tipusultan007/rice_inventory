<?php

namespace App\Models;

use Cassandra\Custom;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SaleReturn
 *
 * @property $id
 * @property $date
 * @property $sale_id
 * @property $customer_id
 * @property $user_id
 * @property $total
 * @property $note
 * @property $attachment
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
		'sale_id' => 'required',
		'customer_id' => 'required',
		'user_id' => 'required',
		'total' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */

    protected $fillable = ['date','sale_id','customer_id','user_id','total','note','attachment','paid','trx_id'];

    public function saleReturnDetail()
    {
        return $this->hasMany(SaleReturnDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

}
