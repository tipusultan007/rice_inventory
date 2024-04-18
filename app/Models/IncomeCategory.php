<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class IncomeCategory
 *
 * @property $id
 * @property $name
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class IncomeCategory extends Model
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

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function total()
    {
        return $this->incomes()->sum('amount');
    }

    public function getTotalAttribute()
    {
        return $this->total();
    }
}
