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
    protected $fillable = ['name','description','value','date','trx_id'];

    public function assetSells()
    {
        return $this->hasMany(AssetSell::class);
    }

    public function getBalanceAttribute()
    {
        return $this->value - $this->assetSells()->sum('purchase_price');
    }

}
