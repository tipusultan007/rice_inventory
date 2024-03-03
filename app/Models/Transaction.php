<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
		'type' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'customer_id',
        'supplier_id',
        'loan_id',
        'amount',
        'type',
        'reference_id',
        'transaction_type',
        'note',
        'cheque_no',
        'cheque_details',
        'date',
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

    public function getTransactionTypeAttribute()
    {
        switch ($this->attributes['transaction_type']) {
            case 'balance_transfer_out':
                return __('ব্যালেন্স স্থানান্তর (বাহিরভুক্ত)');
                break;

            case 'balance_transfer_in':
                return __('ব্যালেন্স স্থানান্তর (অভ্যন্তরভুক্ত)');
                break;

            case 'external_payment_received':
                return __('বাইরে থেকে পেমেন্ট প্রাপ্ত');
                break;

            case 'external_payment_made':
                return __('বাইরে পেমেন্ট হয়েছে');
                break;

            case 'due_payment':
                return __('বকেয়া পেমেন্ট');
                break;

            case 'supplier_payment':
                return __('সরবরাহকারী পেমেন্ট');
                break;

            case 'sale':
                return __('বিক্রয়');
                break;

            case 'purchase':
                return __('ক্রয়');
                break;

            case 'loan_taken':
                return __('লোন সংগ্রহ');
                break;
            case 'loan_repayment':
                return __('লোন পেমেন্ট');
                break;
            case 'loan_interest':
                return __('লোন কমিশন');
                break;
            case 'asset':
                return __('সম্পদ');
                break;
            default:
                return '-';
        }
    }
}
