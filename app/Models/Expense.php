<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Expense
 *
 * @property $id
 * @property $expense_category_id
 * @property $description
 * @property $amount
 * @property $user_id
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Expense extends Model
{

    static $rules = [
		'expense_category_id' => 'required',
		'description' => 'nullable',
		'amount' => 'required',
		'user_id' => 'required',
		'date' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['expense_category_id','date','description','amount','user_id'];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class,'expense_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
