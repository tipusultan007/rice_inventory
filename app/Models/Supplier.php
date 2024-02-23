<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Supplier
 *
 * @property $id
 * @property $name
 * @property $phone
 * @property $address
 * @property $company
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Supplier extends Model
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
    protected $fillable = ['name','phone','address','company'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

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
        return $this->creditSum() - $this->debitSum();
    }
}
