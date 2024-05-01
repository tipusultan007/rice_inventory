<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Asset
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $value
 * @property $date
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Asset extends Model
{

    static $rules = [
		'name' => 'required',
		'value' => 'required',
		'date' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name','description','value','date','trx_id','initial_balance','balance_date'];

    public function assetSells()
    {
        return $this->hasMany(AssetSell::class);
    }

    public function getBalanceAttribute()
    {
        $amount = $this->initial_balance > 0? $this->initial_balance: $this->value;
        return $amount - $this->assetSells()->sum('purchase_price');
    }
    public function initialBalance()
    {
        return $this->hasOne(InitialBalance::class, 'reference_id')->where('type', 'asset');
    }
}
