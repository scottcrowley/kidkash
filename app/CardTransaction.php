<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CardTransaction extends Pivot
{
    /**
     * no timestamps needed
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * A card transaction belongs to one card
     *
     * @return belongsTo
     */
    public function card()
    {
        return $this->belongsTo(Card::class)->with('vendor');
    }

    /**
     * A card transaction belongs to one transaction
     *
     * @return belongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class)->with('vendor')->with('owner');
    }
}
