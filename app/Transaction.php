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
    protected $fillable = ['kid_id', 'vendor_id', 'amount', 'description'];

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
        return '<span class="activity-label-kid">'.$this->kid->name.'</span>'.
            '<span class="activity-label-type">'.(
                ($this->type == 'add') ? ' added <span class="activity-label-amount">$ '.$this->modified_amount.'</span> to ' : ' used <span class="activity-label-amount">$ '.$this->modified_amount.'</span> from '
            ).'</span>'.
            '<span class="activity-label-vendor">'.$this->vendor->name.'</span>';
    }

    public function getKidActivityLabelAttribute()
    {
        return (($this->type == 'add') ? 'Added $ '.$this->modified_amount.' to ' : 'Used $ '.$this->modified_amount.' from ').$this->vendor->name;
    }

    public function getVendorActivityLabelAttribute()
    {
        return $this->kid->name.(($this->type == 'add') ? ' added ' : ' used ').'$ '.$this->modified_amount;
    }
}
