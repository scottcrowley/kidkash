<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_kid', 'avatar_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * getter for whether or not the user has an avatar
     *
     * @return bool
     */
    public function getHasAvatarAttribute()
    {
        return $this->avatar_path != '';
    }

    /**
     * A kid (user) has many transactions
     *
     * @return hasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'kid_id');
    }

    /**
     * A kid (user) has many vendors through related transactions
     *
     * @return hasManyThrough
     */
    public function vendors()
    {
        return $this->hasManyThrough(Vendor::class, Transaction::class, 'kid_id', 'id', 'id', 'vendor_id');
    }
}
