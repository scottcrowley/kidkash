<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Checks if user is parent before any other policies
     *
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        return $this->isParent($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view parent nav items
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function viewNav(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $kid
     * @return bool
     */
    public function update(User $user, User $kid)
    {
        return $user->id == $kid->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $kid
     * @return bool
     */
    public function delete(User $user, User $kid)
    {
        return true;
    }

    /**
     * Check config and user to verify they are a parent
     *
     * @param \App\User $user
     * @return bool
     */
    protected function isParent($user)
    {
        return (! $user->is_kid && in_array($user->email, config('kidkash.parents')));
    }
}
