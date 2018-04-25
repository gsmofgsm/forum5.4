<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the profile.
     *
     * @param User $user
     * @param User $signedInUser
     * @return mixed
     */
    public function update(User $signedInUser, User $user)
    {
        return $signedInUser->id == $user->id;
    }
}
