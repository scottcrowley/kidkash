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
        return $this->hasMany(Transaction::class, 'vendor_id')->with('kid')->latest('updated_at');
    }

    /**
     * Get the total for all transactions
     *
     * @return void
     */
    public function getTransactionTotalsAttribute()
    {
        return number_format($this->transactions->sum('amount'), 2);
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

    public function getKidsListAttribute()
    {
        return $this->kids->unique('id')->values()->each(function ($kid) {
            $kid->vendor_transaction_totals = $kid->transactions()->where('vendor_id', $this->id)->sum('amount');
        });
    }
}
