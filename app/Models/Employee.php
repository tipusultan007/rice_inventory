<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Employee
 *
 * @property $id
 * @property $name
 * @property $phone
 * @property $address
 * @property $image
 * @property $join_date
 * @property $termination_date
 * @property $salary
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Employee extends Model
{

    static $rules = [
		'name' => 'required',
		'salary' => 'required|numeric',
		'status' => 'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name','phone','address','image','join_date','termination_date','salary','status'];

    public function salaries()
    {
        return $this->belongsTo(Salary::class);
    }


}
