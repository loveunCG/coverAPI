<?php

namespace App\Policies;

use App\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function isActive(Admin $user)
    {
        return $user->usr_status == 1;
    }

    public function isSuperAdmin(User $user)
    {
        return $user->admin_level == 1;
    }

    public function isNormalAdmin(User $user)
    {
        return $user->admin_level == 2;
    }
}
