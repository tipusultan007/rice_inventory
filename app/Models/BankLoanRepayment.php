<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BankLoanRepayment
 *
 * @property $id
 * @property $bank_loan__id
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
class BankLoanRepayment extends Model
{

    static $rules = [
		'bank_loan_id' => 'required',
		'date' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['bank_loan_id','user_id','amount','interest','grace','balance','date','trx_id'];


    public function bankLoan()
    {
        return $this->belongsTo(BankLoan::class);
    }

}
