<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AssetSell
 *
 * @property $id
 * @property $asset_id
 * @property $purchase_price
 * @property $sale_price
 * @property $balance
 * @property $notes
 * @property $date
 * @property $trx_id
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class AssetSell extends Model
{

    static $rules = [
		'asset_id' => 'required',
		'purchase_price' => 'required',
		'sale_price' => 'required',
		'date' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['asset_id','purchase_price','sale_price','balance','notes','date','trx_id','account_id'];


    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

}
