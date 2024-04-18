<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Salary
 *
 * @property $id
 * @property $employee_id
 * @property $amount
 * @property $date
 * @property $trx_id
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Salary extends Model
{

    static $rules = [
		'employee_id' => 'required',
		'amount' => 'required|numeric',
		'date' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['employee_id','amount','date','trx_id'];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}
