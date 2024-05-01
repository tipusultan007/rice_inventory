<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BankLoan
 *
 * @property $id
 * @property $name
 * @property $loan_amount
 * @property $interest
 * @property $duration
 * @property $total_loan
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
class BankLoan extends Model
{

    static $rules = [
		'name' => 'required',
		'loan_amount' => 'required',
		'interest' => 'required',
		'duration' => 'required',
		'date' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name','loan_amount','interest','duration','total_loan','grace','date','expire','trx_id','description','initial_balance','balance_date'];

    public function loanRepayments()
    {
        return $this->hasMany(BankLoanRepayment::class);
    }
    public function getTotalInterestAttribute()
    {
        return $this->loanRepayments()->sum('interest');
    }
    public function getGraceAttribute()
    {
        return $this->loanRepayments()->sum('grace');
    }
    public function getBalanceAttribute()
    {
        $balance = $this->initial_balance>0?$this->initial_balance:$this->total_loan;
        $paid = $this->loanRepayments()->sum('amount');
        $grace = $this->loanRepayments()->sum('grace');
        //return $this->total_loan -$this->initial_balance - $paid - $grace;
        return $balance - $paid - $grace;
    }


    public function initialBalance()
    {
        return $this->hasOne(InitialBalance::class, 'reference_id')->where('type', 'bank_loan');
    }
}
