<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InvestmentRepayment
 *
 * @property $id
 * @property $investment_repayment_id
 * @property $user_id
 * @property $amount
 * @property $interest
 * @property $grace
 * @property $balance
 * @property $date
 * @property $trx_id
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class InvestmentRepayment extends Model
{

    static $rules = [
		'investment_id' => 'required',
		'user_id' => 'required',
		'date' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['investment_id','user_id','amount','interest','grace','balance','date','trx_id'];


    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }

}
