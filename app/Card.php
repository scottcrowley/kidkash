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
        return $this->belongsToMany(Transaction::class)->using(CardTransaction::class);
    }
}
