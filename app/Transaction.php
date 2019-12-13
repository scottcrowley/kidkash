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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['type'];

    /**
     * A transaction belongs to one owner (user)
     *
     * @return belongsTo
     */
    public function owner()
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
     * A from transaction can have one transfer
     *
     * @return hasOne
     */
    public function transferFrom()
    {
        return $this->hasOne(Transfer::class, 'from_transaction_id');
    }

    /**
     * A from transaction can have one transfer
     *
     * @return hasOne
     */
    public function transferTo()
    {
        return $this->hasOne(Transfer::class, 'to_transaction_id');
    }

    /**
     * Check to see if transaction is part of transfer
     *
     * @return bool
     */
    public function getHasTransferAttribute()
    {
        return ($this->transferFrom || $this->transferTo);
    }

    /**
     * Format the amount attribute
     *
     * @param mixed $value
     * @return float
     */
    public function getAmountAttribute($value)
    {
        return number_format($value, 2);
    }

    /**
     * getter for number of associated card
     *
     * @return string
     */
    public function getNumberAttribute()
    {
        return ($this->card) ? $this->card->number : '';
    }

    /**
     * getter for pin number related to associated card
     *
     * @return string
     */
    public function getPinAttribute()
    {
        return ($this->card) ? $this->card->pin : '';
    }

    /**
     * custom getter for transaction type
     *
     * @return string
     */
    public function getTypeAttribute()
    {
        if ($this->amount == 0) {
            return '0';
        }
        return ($this->amount > 0) ? 'add' : 'use';
    }

    /**
     * formats the amount column
     *
     * @return float
     */
    public function getModifiedAmountAttribute()
    {
        return number_format(abs($this->amount), 2);
    }

    /**
     * creates an activity label
     *
     * @return string
     */
    public function getActivityLabelAttribute()
    {
        return '<span class="activity-label-owner">'.$this->owner->name.'</span>'.
            '<span class="activity-label-type">'.(
                ($this->type == 'add') ? ' added <span class="activity-label-amount">$'.$this->modified_amount.'</span> to ' : ' used <span class="activity-label-amount">$'.$this->modified_amount.'</span> from '
            ).'</span>'.
            '<span class="activity-label-vendor">'.$this->vendor->name.'</span>';
    }

    /**
     * creates an owner activity label
     *
     * @return string
     */
    public function getOwnerActivityLabelAttribute()
    {
        return (($this->type == 'add') ? 'Added $ '.$this->modified_amount.' to ' : 'Used $'.$this->modified_amount.' from ').$this->vendor->name;
    }

    /**
     * creates a vendor activity label
     *
     * @return string
     */
    public function getVendorActivityLabelAttribute()
    {
        return $this->owner->name.(($this->type == 'add') ? ' added ' : ' used ').'$'.$this->modified_amount;
    }
}
