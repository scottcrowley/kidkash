<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vendor_id', 'number', 'pin'];

    /**
     * A card belongs to one vendor
     *
     * @return belongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * A card belongs to many transactions
     *
     * @return belongsToMany
     */
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class)->using(CardTransaction::class)->latest();
    }

    /**
     * Get balance of card
     *
     * @return float
     */
    public function getBalanceAttribute()
    {
        return $this->transactions->sum('amount');
    }

    /**
     * Gets all owners associated with the card
     *
     * @return \Illuminate\Support\Collection
     */
    public function getOwnersAttribute()
    {
        return $this->transactions->pluck('owner')->unique()->each(function ($owner) {
            $owner->card_transactions = $this->transactions->where('owner_id', $owner->id);
            $owner->card_transaction_totals = $owner->card_transactions->sum('amount');
        });
    }

    /**
     * Gets a string version of the owner names
     *
     * @return string
     */
    public function getOwnerNamesAttribute()
    {
        $names = $this->owners->pluck('name');

        return $names->join(', ', ' & ');
    }
}
