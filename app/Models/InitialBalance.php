<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InitialBalance
 *
 * @property $id
 * @property $amount
 * @property $date
 * @property $type
 * @property $reference_id
 * @property $trx_id
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class InitialBalance extends Model
{

    static $rules = [
		'amount' => 'required',
		'date' => 'required',
		'type' => 'required',
		'reference_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['amount','date','type','reference_id','trx_id'];

    public function account()
    {
        return $this->belongsTo(Account::class, 'reference_id')->where('type', 'account');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'reference_id')->where('type', 'asset');
    }

    public function bankLoan()
    {
        return $this->belongsTo(BankLoan::class, 'reference_id')->where('type', 'bank_loan');
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'reference_id')->where('type', 'loan');
    }

    public function capital()
    {
        return $this->belongsTo(Capital::class, 'reference_id')->where('type', 'capital');
    }

    public function investment()
    {
        return $this->belongsTo(Investment::class, 'reference_id')->where('type', 'investment');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'reference_id')->where('type', 'customer');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'reference_id')->where('type', 'supplier');
    }

}
