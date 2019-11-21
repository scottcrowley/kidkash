<?php

namespace App;

use Illuminate\Support\Str;
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
        'name', 'email', 'password', 'avatar_path'
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_authorized_parent', 'has_avatar'];

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
        return $this->hasMany(Transaction::class, 'owner_id')->latest()->with('vendor');
    }

    /**
     * A kid (user) has many vendors through related transactions
     *
     * @return hasManyThrough
     */
    public function vendors()
    {
        return $this->hasManyThrough(Vendor::class, Transaction::class, 'owner_id', 'id', 'id', 'vendor_id');
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
     * Get transactions for each associated vendor
     *
     * @return void
     */
    public function getVendorsListAttribute()
    {
        $vendors = [];

        foreach ($this->transactions as $transaction) {
            $id = $transaction->vendor->id;
            $vendors[$id] = $transaction->vendor;
            if (! isset($vendors[$id]->owner_transactions)) {
                $vendors[$id]->owner_transactions = collect([]);
            }
            $vendors[$id]->owner_transactions->push($transaction);
        };

        $vendors = (collect($vendors))->sortBy('name')->values()->each(function ($vendor) {
            $vendor->owner_transaction_totals = $vendor->owner_transactions->sum('amount');
        });

        return $vendors;
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

    /**
     * Checks to see if user is an authorized parent
     *
     * @return bool
     */
    public function getIsAuthorizedParentAttribute()
    {
        return (in_array($this->email, config('kidkash.parents')));
    }
}
