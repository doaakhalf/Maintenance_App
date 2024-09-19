<?php

namespace App\Policies;

use App\Models\MaintenancePerform;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaintenancePerformPolicy extends BacePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
        return $this->isAdmin($user) ||$this->isManager($user) || $this->isTechnician($user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaintenancePerform  $maintenancePerform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, MaintenancePerform $maintenancePerform)
    {
        //
        // Check if the user has an Admin role
        if ($this->isAdmin($user)) {
            return true;
        }

        // Check if the user is a Manager and is either the requester or performed the maintenance
        if ($user->hasRole('Manager')) {
            return $maintenancePerform->requester_id == $user->id || $maintenancePerform->performed_by_id == $user->id;
        }

        // Check if the user is a Technician and meets any of the Technician-specific conditions
        if ($this->isTechnician($user)) {
            return $maintenancePerform->technician_id == $user->id ||
                $maintenancePerform->performed_by_id == $user->id ||
                $maintenancePerform->maintenanceRequest->assignments->contains('assigned_to_id', $user->id);
        }

        // If none of the above conditions are met, return false
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
        return $this->isAdmin($user) ||$this->isManager($user) || $this->isTechnician($user);
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaintenancePerform  $maintenancePerform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, MaintenancePerform $maintenancePerform)
    {
        //
        // Check if the user has an Admin role
        if ($this->isAdmin($user)) {
            return true;
        }

        // Check if the user is a Manager and either requested or performed the maintenance
        if ($user->hasRole('Manager')) {
            return $maintenancePerform->requester_id == $user->id ||
                $maintenancePerform->performed_by_id == $user->id;
        }

        // Check if the user is a Technician and performed the maintenance
        if ($this->isTechnician($user)) {
            return $maintenancePerform->performed_by_id == $user->id;
        }

        // Return false if none of the above conditions are met
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaintenancePerform  $maintenancePerform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, MaintenancePerform $maintenancePerform)
    {
        // Check if the maintenance perform status is not 'InProgress'
        // if ($maintenancePerform->status === 'InProgress') {
        //     return false;
        // }

        // Check if the user has an Admin role
        if ($this->isAdmin($user)) {
            return true;
        }

        // Check if the user is a Manager and either requested or performed the maintenance
        if ($user->hasRole('Manager')) {
            return $maintenancePerform->requester_id == $user->id ||
                $maintenancePerform->performed_by_id == $user->id;
        }

        // Check if the user is a Technician and performed the maintenance
        if ($this->isTechnician($user)) {
            return $maintenancePerform->performed_by_id == $user->id;
        }

        // Return false if none of the conditions are met
        return false;

        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaintenancePerform  $maintenancePerform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, MaintenancePerform $maintenancePerform)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MaintenancePerform  $maintenancePerform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, MaintenancePerform $maintenancePerform)
    {
        //
    }
}
