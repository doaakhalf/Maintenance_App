<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BacePolicy
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
      /**
     * Helper method to check if the user has the Admin role.
     */
    protected function isAdmin(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Helper method to check if the user has the Manager role.
     */
    protected function isManager(User $user): bool
    {
        return $user->hasRole('Manager');
    }

    /**
     * Helper method to check if the user has the Technician role.
     */
    protected function isTechnician(User $user): bool
    {
        return $user->hasRole('Technician');
    }
}
