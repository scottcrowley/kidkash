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
     * @return float
     */
    public function getTransactionTotalsAttribute()
    {
        return $this->transactions->sum('raw_amount');
    }

    /**
     * Get transactions for each associated vendor
     *
     * @return \Illuminate\Support\Collection
     */
    public function getVendorsListAttribute()
    {
        $vendors = [];

        foreach ($this->transactions as $transaction) {
            $id = $transaction->vendor->id;
            if (! isset($vendors[$id])) {
                $vendors[$id] = $transaction->vendor;
            }
            if (! $vendors[$id]->owner_transactions) {
                $vendors[$id]->owner_transactions = collect([]);
            }
            $vendors[$id]->owner_transactions->push($transaction);
        };

        $vendors = (collect($vendors))
            ->filter(function ($vendor) {
                return number_format($vendor->owner_transactions->sum('raw_amount'), 2) != 0;
            })
            ->sortBy('name')
            ->values()
            ->each(function ($vendor) {
                $vendor->owner_transaction_totals = $vendor->owner_transactions->sum('raw_amount');
                $vendor = $vendor->makeHidden(['owner_transactions']);
            });

        return $vendors;
    }

    /**
     * Get all cards associated with transactions
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCardsListAttribute()
    {
        $cards = [];
        $transactions = $this->transactions->load('card');
        foreach ($transactions as $transaction) {
            if (! $transaction->card) {
                continue;
            }

            $id = $transaction->card->id;
            if (! isset($cards[$id])) {
                $cards[$id] = $transaction->card;
            }
            $cards[$id]->card_balance =
                ($cards[$id]->card_balance) ?
                    $cards[$id]->card_balance + $transaction->raw_amount : $transaction->raw_amount;
            $cards[$id]->vendor_name = $transaction->vendor->name;
        }

        $cards = (collect($cards))
            ->sortBy('vendor_name')
            ->values()
            ->groupBy('vendor_name')
            ->filter(function ($vendor) {
                return $vendor->sum('card_balance') > 0;
            })
            ->map(function ($vendor) {
                return $vendor->filter(function ($card) {
                    return $card->card_balance > 0;
                });
            });

        return $cards;
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
