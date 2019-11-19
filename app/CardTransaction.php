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
}
