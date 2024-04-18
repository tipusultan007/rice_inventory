<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LoanRepayment
 *
 * @property $id
 * @property $loan_id
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
class LoanRepayment extends Model
{

    static $rules = [
		'loan_id' => 'required',
		'user_id' => 'required',
		'date' => 'required',
		'trx_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loan_id',
        'user_id',
        'amount',
        'interest',
        'balance',
        'total_interest',
        'date',
        'trx_id'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }


}
