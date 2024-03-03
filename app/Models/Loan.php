<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Loan
 *
 * @property $id
 * @property $loan_amount
 * @property $interest_rate
 * @property $date
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Loan extends Model
{

    static $rules = [
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
    protected $fillable = ['loan_amount','interest_rate','date','description','balance'];


    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function paidLoan()
    {
        return $this->transactions()
            ->where('transaction_type','loan_repayment')
            ->sum('amount');
    }

    public function paidInterest()
    {
        return $this->transactions()
            ->where('transaction_type','loan_interest')
            ->sum('amount');
    }

    /*public function getBalanceAttribute()
    {
        return $this->loan_amount - $this->paidLoan();
    }*/

    public function getPaidInterestAttribute()
    {
        return $this->paidInterest();
    }

}
