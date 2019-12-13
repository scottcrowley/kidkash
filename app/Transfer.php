<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['from_transaction_id', 'to_transaction_id'];

    /**
     * A transfer has one from_transaction
     *
     * @return hasOne
     */
    public function fromTransaction()
    {
        return $this->hasOne(Transaction::class, 'id', 'from_transaction_id');
    }

    /**
     * A transfer has one to_transaction
     *
     * @return hasOne
     */
    public function toTransaction()
    {
        return $this->hasOne(Transaction::class, 'id', 'to_transaction_id');
    }
}
