<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Capital
 *
 * @property $id
 * @property $amount
 * @property $profit_rate
 * @property $balance
 * @property $date
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Capital extends Model
{

    static $rules = [
		'amount' => 'required',
		'profit_rate' => 'required',
		'date' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name','amount','profit_rate','date','trx_id','description','initial_balance','balance_date'];


    public function capitalWithdraws()
    {
        return $this->hasMany(CapitalWithdraw::class);
    }

    public function getCapitalProfitAttribute()
    {
        return $this->capitalWithdraws()->sum('interest');
    }

    public function getBalanceAttribute()
    {
        $amount = $this->initial_balance > 0? $this->initial_balance: $this->amount;
        return $amount - $this->capitalWithdraws()->sum('amount');
    }
    public function initialBalance()
    {
        return $this->hasOne(InitialBalance::class, 'reference_id')->where('type', 'capital');
    }
}
