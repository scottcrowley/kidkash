<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['kid_id', 'vendor_id', 'type', 'amount'];

    /**
     * A transaction belongs to one kid (user)
     *
     * @return belongsTo
     */
    public function kid()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A transaction belongs to one vendor
     *
     * @return belongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
