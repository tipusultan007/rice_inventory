<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Customer
 *
 * @property $id
 * @property $name
 * @property $phone
 * @property $address
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Customer extends Model
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
    protected $fillable = ['name','phone','address'];


    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function debitSum()
    {
        return $this->payments()->where('type', 'debit')->sum('amount');
    }

    public function creditSum()
    {
        return $this->payments()->where('type', 'credit')->sum('amount');
    }

    public function getRemainingDueAttribute()
    {
        return $this->debitSum() - $this->creditSum();
    }

}
