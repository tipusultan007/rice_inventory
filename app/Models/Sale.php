<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Sale
 *
 * @property $id
 * @property $sale_date
 * @property $customer_id
 * @property $user_id
 * @property $total
 * @property $additional_field
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Sale extends Model
{

    static $rules = [
		'date' => 'required',
		'customer_id' => 'required',
		'user_id' => 'required',
		'subtotal' => 'required',
		'total' => 'required',
		'invoice_no' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'customer_id',
        'user_id',
        'invoice_no',
        'book_no',
        'subtotal',
        'dholai',
        'discount',
        'total',
        'note',
        'due',
        'paid',
        'attachment',
        'trx_id'
    ];

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function generateInvoiceNumber()
    {
        $lastSale = Sale::latest()->first();

        $lastInvoiceNumber = $lastSale ? $lastSale->invoice_no : 0;

        // Increment the last invoice number
        $newInvoiceNumber = $lastInvoiceNumber + 1;

        return $newInvoiceNumber;
    }
}
