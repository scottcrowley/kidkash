<?php

namespace App;

use Illuminate\Support\Str;
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
     * Get the route key name.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * A vendor has many transactions
     *
     * @return hasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'vendor_id')->with('owner')->latest('updated_at');
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
     * A vendor has many related owners through transactions
     *
     * @return hasManyThrough
     */
    public function owners()
    {
        return $this->hasManyThrough(User::class, Transaction::class, 'vendor_id', 'id', 'id', 'user_id');
    }

    /**
     * Get individual owners with related transactions and sum all transactions
     *
     * @return void
     */
    public function getOwnersListAttribute()
    {
        return $this->owners->unique('id')->values()->each(function ($owner) {
            $owner->vendor_transaction_totals = $owner->transactions()->where('vendor_id', $this->id)->sum('amount');
        });
    }

    /**
     * Set the name & slug attributes
     *
     * @param mixed $name
     * @return void
     */
    public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;
        $this->attributes['slug'] = Str::slug($name);
    }
}
