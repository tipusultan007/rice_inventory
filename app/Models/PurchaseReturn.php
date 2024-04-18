<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PurchaseReturn
 *
 * @property $id
 * @property $date
 * @property $purchase_id
 * @property $supplier_id
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
class PurchaseReturn extends Model
{

    static $rules = [
		'date' => 'required',
		'purchase_id' => 'required',
		'supplier_id' => 'required',
		'user_id' => 'required',
		'total' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['date','purchase_id','supplier_id','user_id','total','paid','note','attachment', 'trx_id'];

    public function purchaseReturnDetail()
    {
        return $this->hasMany(PurchaseReturnDetail::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

}
