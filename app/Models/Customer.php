<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Customer
 *
 * @property $id
 * @property $name
 * @property $phone
 * @property $address
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Customer extends Model
{

    static $rules = [
		'name' => 'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */

    protected $appends = ['remaining_due'];
    protected $fillable = ['name','phone','address','image','starting_balance'];


    public function customerDue()
    {
        return $this->payments()->where('transaction_type','sale')
            ->where('type', 'credit')
            ->sum('amount');
    }

    public function customerPayment()
    {
        return $this->payments()->where('transaction_type','customer_payment')
            ->where('type', 'debit')
            ->sum('amount');
    }
    public function payments()
    {
        return $this->hasMany(Transaction::class);
    }
    public function getRemainingDueAttribute()
    {
        $total = DB::table('transactions')
            ->select(DB::raw('SUM(
            CASE
                WHEN transaction_type = "customer_opening_balance" AND type = "debit" THEN amount
                WHEN transaction_type = "sale" AND type = "credit" THEN amount
                WHEN transaction_type = "customer_payment" AND type = "debit" THEN -amount
                WHEN transaction_type = "discount" AND type = "debit" THEN -amount
                WHEN transaction_type = "payment_to_customer" AND type = "credit" THEN -amount
                ELSE 0
            END
        ) AS total_due'))
            ->where('customer_id', $this->id)
            ->value('total_due');

        return $total;
    }
    /*public function getRemainingDueAttribute()
    {
        $total = $this->payments()->where('transaction_type','customer_opening_balance')
            ->where('type', 'debit')
            ->sum('amount');

        $total += $this->payments()->where('transaction_type','sale')
            ->where('type', 'credit')
            ->sum('amount');

        $total -= $this->payments()->where('transaction_type','customer_payment')
            ->where('type', 'debit')
            ->sum('amount');
        $total -= $this->payments()->where('transaction_type','discount')
            ->where('type', 'debit')
            ->sum('amount');
        $total -= $this->payments()->where('transaction_type','payment_to_customer')
            ->where('type', 'credit')
            ->sum('amount');

        return $total;

    }*/

    public function saleReturn()
    {
        return $this->payments()->where('transaction_type','sale_return')
                ->where('type', 'debit')
                ->sum('amount') - $this->payments()->where('transaction_type','payment_to_customer')
                ->where('type', 'credit')
                ->sum('amount');
    }
}
