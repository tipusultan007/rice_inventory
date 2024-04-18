<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tohori
 *
 * @property $id
 * @property $amount
 * @property $date
 * @property $trx_id
 * @property $user_id
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Tohori extends Model
{

    static $rules = [
		'amount' => 'required',
		'date' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['amount','date','trx_id','user_id'];



}
