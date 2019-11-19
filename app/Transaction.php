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
    protected $fillable = ['owner_id', 'vendor_id', 'amount', 'description'];

    /**
     * A transaction belongs to one owner (user)
     *
     * @return belongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
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

    /**
     * A transaction can have one card
     *
     * @return hasOneThrough
     */
    public function card()
    {
        return $this->hasOneThrough(Card::class, CardTransaction::class, 'transaction_id', 'id', 'id', 'card_id');
    }

    /**
     * Format the amount attribute
     *
     * @param mixed $value
     * @return void
     */
    public function getAmountAttribute($value)
    {
        return number_format($value, 2);
    }

    public function getTypeAttribute()
    {
        return ($this->amount > 0) ? 'add' : 'use';
    }

    public function getModifiedAmountAttribute()
    {
        return number_format(abs($this->amount), 2);
    }

    public function getActivityLabelAttribute()
    {
        return '<span class="activity-label-owner">'.$this->owner->name.'</span>'.
            '<span class="activity-label-type">'.(
                ($this->type == 'add') ? ' added <span class="activity-label-amount">$ '.$this->modified_amount.'</span> to ' : ' used <span class="activity-label-amount">$ '.$this->modified_amount.'</span> from '
            ).'</span>'.
            '<span class="activity-label-vendor">'.$this->vendor->name.'</span>';
    }

    public function getOwnerActivityLabelAttribute()
    {
        return (($this->type == 'add') ? 'Added $ '.$this->modified_amount.' to ' : 'Used $ '.$this->modified_amount.' from ').$this->vendor->name;
    }

    public function getVendorActivityLabelAttribute()
    {
        return $this->owner->name.(($this->type == 'add') ? ' added ' : ' used ').'$ '.$this->modified_amount;
    }
}
