<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'url'];

    /**
     * A vendor has many transactions
     *
     * @return hasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'vendor_id');
    }

    /**
     * A vendor has many related kids through transactions
     *
     * @return hasManyThrough
     */
    public function kids()
    {
        return $this->hasManyThrough(User::class, Transaction::class, 'vendor_id', 'id', 'id', 'kid_id');
    }
}
