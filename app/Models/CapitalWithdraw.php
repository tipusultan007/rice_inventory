<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CapitalWithdraw
 *
 * @property $id
 * @property $capital_id
 * @property $user_id
 * @property $amount
 * @property $interest
 * @property $balance
 * @property $date
 * @property $trx_id
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class CapitalWithdraw extends Model
{

    static $rules = [
		'capital_id' => 'required',
		'user_id' => 'required',
		'balance' => 'required',
		'date' => 'required',
		'trx_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['capital_id','user_id','amount','interest','balance','total_interest','date','trx_id'];


    public function capital()
    {
        return $this->belongsTo(Capital::class);
    }

}
