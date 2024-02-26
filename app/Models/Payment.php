<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Due
 *
 * @property $id
 * @property $customer_id
 * @property $supplier_id
 * @property $amount
 * @property $type
 * @property $invoice
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Payment extends Model
{

    static $rules = [
		'amount' => 'required',
		'type' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'supplier_id',
        'amount',
        'type',
        'invoice',
        'user_id',
        'date',
        'payment_method_id',
        'cheque_no',
        'note',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Scope to get total debit amount for a customer
    public function scopeTotalDebitForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId)->where('type', 'debit')->sum('amount');
    }

    // Scope to get total credit amount for a customer
    public function scopeTotalCreditForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId)->where('type', 'credit')->sum('amount');
    }

    // Scope to get total debit amount for a supplier
    public function scopeTotalDebitForSupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId)->where('type', 'debit')->sum('amount');
    }

    // Scope to get total credit amount for a supplier
    public function scopeTotalCreditForSupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId)->where('type', 'credit')->sum('amount');
    }

}
