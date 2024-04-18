<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Purchase
 *
 * @property $id
 * @property $purchase_date
 * @property $supplier_id
 * @property $user_id
 * @property $total
 * @property $additional_field
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Purchase extends Model
{

    static $rules = [
        'purchase_date' => 'required|date',
        'supplier_id' => 'required|exists:suppliers,id',
        'user_id' => 'required|exists:users,id',
        'subtotal' => 'required|numeric',
        'total' => 'required|numeric',
        'note' => 'nullable|string',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.amount' => 'required|numeric|min:0',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.price_rate' => 'required|numeric|min:0',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'supplier_id',
        'user_id',
        'invoice_no',
        'truck_no',
        'subtotal',
        'carrying_cost',
        'tohori',
        'discount',
        'total',
        'note',
        'due',
        'paid',
        'attachment',
        'trx_id'
    ];


    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generateInvoiceNumber()
    {
        $lastPurchase = Purchase::latest()->first();

        $lastInvoiceNumber = $lastPurchase ? $lastPurchase->invoice_no : 0;

        // Increment the last invoice number
        $newInvoiceNumber = $lastInvoiceNumber + 1;

        return $newInvoiceNumber;
    }
}
