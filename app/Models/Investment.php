<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Investment
 *
 * @property $id
 * @property $name
 * @property $loan_amount
 * @property $interest_rate
 * @property $grace
 * @property $date
 * @property $trx_id
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Investment extends Model
{

    static $rules = [
		'name' => 'required',
		'loan_amount' => 'required',
		'interest_rate' => 'required',
		'date' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name','loan_amount','interest_rate','grace','date','trx_id','description'];

    public function investmentRepayments()
    {
        return $this->hasMany(InvestmentRepayment::class);
    }

    public function getTotalPaidAttribute()
    {
        return $this->investmentRepayments()->sum('amount');
    }

    public function getTotalInterestAttribute()
    {
        return $this->investmentRepayments()->sum('interest');
    }

    public function getBalanceAttribute()
    {
        return $this->loan_amount - $this->investmentRepayments()->sum('amount');
    }

}
