<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    /**
     * A card belongs to one owner (user)
     *
     * @return belongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A card belongs to one owner (user)
     *
     * @return belongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
