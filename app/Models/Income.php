<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Income
 *
 * @property $id
 * @property $income_category_id
 * @property $description
 * @property $date
 * @property $amount
 * @property $user_id
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Income extends Model
{

    static $rules = [
		'income_category_id' => 'required',
		'date' => 'required',
		'amount' => 'required',
		'account_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['income_category_id','description','date','amount','user_id','trx_id'];

    public function category()
    {
        return $this->belongsTo(IncomeCategory::class,'income_category_id');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'reference_id')->where('transaction_type', 'income');
    }
}
