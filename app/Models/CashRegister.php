<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CashRegister
 *
 * @property $id
 * @property $opening_balance
 * @property $ending_balance
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class CashRegister extends Model
{
    
    static $rules = [
		'opening_balance' => 'required',
		'ending_balance' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['opening_balance','ending_balance'];



}
