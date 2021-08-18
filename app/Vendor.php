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
     * A vendor has many transactions
     *
     * @return hasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'vendor_id')->latest()->with('owner');
    }

    /**
     * A vendor has many cards
     *
     * @return hasMany
     */
    public function cards()
    {
        return $this->hasMany(Card::class, 'vendor_id');
    }

    /**
     * A vendor has many related owners through transactions
     *
     * @return hasManyThrough
     */
    public function owners()
    {
        return $this->hasManyThrough(User::class, Transaction::class, 'vendor_id', 'id', 'id', 'owner_id');
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
     * Get the total for all transactions
     *
     * @return float
     */
    public function getCardBalanceTotalsAttribute()
    {
        return $this->cards->sum('balance');
    }

    /**
     * Get individual owners with related transactions and has a balance
     *
     * @return \Illuminate\Support\Collection
     */
    public function getOwnersListAttribute()
    {
        $owners = [];

        foreach ($this->transactions as $transaction) {
            $id = $transaction->owner->id;
            $owners[$id] = $transaction->owner;
            if (! isset($owners[$id]->vendor_transactions)) {
                $owners[$id]->vendor_transactions = collect([]);
            }
            $owners[$id]->vendor_transactions->push($transaction);
        };

        $owners = (collect($owners))
            ->filter(function ($owner) {
                return number_format($owner->vendor_transactions->sum('raw_amount'), 2) != 0;
            })
            ->sortBy('name')
            ->values()
            ->each(
                function ($owner) {
                    $owner->vendor_transaction_totals = $owner->vendor_transactions->sum('raw_amount');
                }
            );

        return $owners;
    }

    /**
     * Get all cards associated with transactions and has a balance
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCardsListAttribute()
    {
        return $this->cards
            ->filter(
                function ($card) {
                    return $card->transactions->isNotEmpty() && $card->balance > 0;
                }
            )
            ->values();
    }

    /**
     * Get all cards associated with transactions with a zero balance
     *
     * @return \Illuminate\Support\Collection
     */
    public function getEmptyCardsListAttribute()
    {
        return $this->cards
            ->filter(
                function ($card) {
                    return $card->transactions->isNotEmpty() && $card->balance <= 0;
                }
            )
            ->values();
    }

    /**
     * Set the name & slug attributes
     *
     * @param string $name
     * @return void
     */
    public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;
        $this->attributes['slug'] = Str::slug($name);
    }
}
