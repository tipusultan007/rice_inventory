<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BalanceTransfer
 *
 * @property $id
 * @property $from_account_id
 * @property $to_account_id
 * @property $amount
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class BalanceTransfer extends Model
{

    static $rules = [
		'from_account_id' => 'required',
		'to_account_id' => 'required',
		'amount' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['from_account_id','to_account_id','amount','date','note','trx_id'];

    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

}
