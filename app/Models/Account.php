<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    protected $fillable = ['name','details','starting_balance','date'];

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
        $total = DB::table('transactions')
            ->select(DB::raw('SUM(
            CASE
                WHEN type = "debit" THEN amount
                WHEN type = "credit" THEN -amount
                ELSE 0
            END
        ) AS total_due'))
            ->where('account_id', $this->id)
            ->value('total_due');

        return $total;
        //return $this->creditSum() - $this->debitSum();
    }
}
