<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_kid', 'avatar_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * getter for whether or not the user has an avatar
     *
     * @return bool
     */
    public function getHasAvatarAttribute()
    {
        return $this->avatar_path != '';
    }

    /**
     * A kid (user) has many transactions
     *
     * @return hasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'kid_id')->latest('updated_at')->with('vendor');
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
     * A kid (user) has many vendors through related transactions
     *
     * @return hasManyThrough
     */
    public function vendors()
    {
        return $this->hasManyThrough(Vendor::class, Transaction::class, 'kid_id', 'id', 'id', 'vendor_id');
    }

    /**
     * Get transactions for each vendor
     *
     * @return void
     */
    public function getVendorsListAttribute()
    {
        return $this->vendors->unique('name')->each(function ($vendor) {
            $vendor->kid_transactions = $vendor->transactions()->where('kid_id', $this->id)->without('kid')->latest('updated_at')->get();
            $vendor->kid_transaction_totals = $vendor->kid_transactions->sum('amount');
        });
    }
}
