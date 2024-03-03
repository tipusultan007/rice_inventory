<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Account
 *
 * @property $id
 * @property $name
 * @property $details
 * @property $balance
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Account extends Model
{

    static $rules = [
		'name' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name','details'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function balanceTransfersFrom()
    {
        return $this->hasMany(BalanceTransfer::class, 'from_account_id');
    }

    public function balanceTransfersTo()
    {
        return $this->hasMany(BalanceTransfer::class, 'to_account_id');
    }


    public function debitSum()
    {
        return $this->transactions()->where('type', 'debit')->sum('amount');
    }

    public function creditSum()
    {
        return $this->transactions()->where('type', 'credit')->sum('amount');
    }

    public function getBalanceAttribute()
    {
        return $this->creditSum() - $this->debitSum();
    }
}
