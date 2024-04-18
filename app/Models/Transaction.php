<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Transaction
 *
 * @property $id
 * @property $account_id
 * @property $customer_id
 * @property $supplier_id
 * @property $amount
 * @property $type
 * @property $reference_id
 * @property $transaction_type
 * @property $note
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Transaction extends Model
{

    static $rules = [
		'amount' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'account_name',
        'trx_id',
        'customer_id',
        'supplier_id',
        'amount',
        'balance',
        'type',
        'reference_id',
        'transaction_type',
        'note',
        'cheque_no',
        'cheque_details',
        'date',
        'user_id'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function capital()
    {
        return $this->belongsTo(Capital::class);
    }
    public function asset()
    {
        return $this->belongsTo(Asset::class,'reference_id');
    }

    public function incomeCategory()
    {
        return $this->belongsTo(IncomeCategory::class);
    }

    public function income()
    {
        return $this->belongsTo(Income::class);
    }

    public function expenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
