<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ExpenseCategory
 *
 * @property $id
 * @property $name
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ExpenseCategory extends Model
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
    protected $fillable = ['name'];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    public function total()
    {
        return $this->expenses()->sum('amount');
    }

    public function getTotalAttribute()
    {
        return $this->total();
    }
}
