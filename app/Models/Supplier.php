<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Supplier
 *
 * @property $id
 * @property $name
 * @property $phone
 * @property $address
 * @property $company
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Supplier extends Model
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
    protected $fillable = ['name','phone','address','company','image','starting_balance'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function payments()
    {
        return $this->hasMany(Transaction::class);
    }
    public function supplierDue()
    {
        /*$purchaseDue = $this->payments()->where('transaction_type','purchase')
            ->where('type', 'debit')
            ->sum('amount');
        $supplierDue = $this->payments()->where('transaction_type','supplier_due')
            ->where('type', 'credit')
            ->sum('amount');*/
        return $this->payments()->where('transaction_type','purchase')
            ->where('type', 'debit')
            ->sum('amount');
    }

    public function supplierPayment()
    {
        return $this->payments()->where('transaction_type','supplier_payment')
            ->where('type', 'credit')
            ->sum('amount');
    }

    /*public function getRemainingDueAttribute()
    {
        $total = $this->payments()->where('transaction_type','supplier_opening_balance')
            ->where('type', 'credit')
            ->sum('amount');
        $total += $this->payments()->where('transaction_type','purchase')
            ->where('type', 'debit')
            ->sum('amount');
        $total -= $this->payments()->where('transaction_type','supplier_payment')
            ->where('type', 'credit')
            ->sum('amount');
        $total -= $this->payments()->where('transaction_type','discount')
            ->where('type', 'credit')
            ->sum('amount');
        $total -= $this->payments()->where('transaction_type','payment_from_supplier')
            ->where('type', 'debit')
            ->sum('amount');
        return $total;
    }*/
    public function getRemainingDueAttribute()
    {
        $total = DB::table('transactions')
            ->select(DB::raw('SUM(
            CASE
                WHEN transaction_type = "supplier_opening_balance" AND type = "credit" THEN amount
                WHEN transaction_type = "purchase" AND type = "debit" THEN amount
                WHEN transaction_type = "supplier_payment" AND type = "credit" THEN -amount
                WHEN transaction_type = "discount" AND type = "credit" THEN -amount
                WHEN transaction_type = "payment_from_supplier" AND type = "debit" THEN -amount
                ELSE 0
            END
        ) AS total_due'))
            ->where('supplier_id', $this->id)
            ->value('total_due');

        return $total;
    }



    public function purchaseReturn()
    {
        return $this->payments()->where('transaction_type','purchase_return')
            ->where('type', 'credit')
            ->sum('amount') - $this->payments()->where('transaction_type','payment_from_supplier')
                ->where('type', 'debit')
                ->sum('amount');
    }
}
