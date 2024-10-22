<?php

namespace App\Policies;

use App\Models\CalibrationPerform;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalibrationPerformPolicy extends BacePolicy

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
     * @param  \App\Models\CalibrationPerform  $calibrationPerform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CalibrationPerform $calibrationPerform)
    {
        //
       
        // Check if the user has an Admin role
        if ($this->isAdmin($user)) {
            return true;
        }

        // Check if the user is a Manager and is either the requester or performed the maintenance
        if ($user->hasRole('Manager')) {
            return $calibrationPerform->requester_id == $user->id || $calibrationPerform->performed_by_id == $user->id;
        }

        // Check if the user is a Technician and meets any of the Technician-specific conditions
        if ($this->isTechnician($user)) {
            return $calibrationPerform->technician_id == $user->id ||
                $calibrationPerform->performed_by_id == $user->id ||
                $calibrationPerform->calibrationRequest->assignments->contains('assigned_to_id', $user->id);
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
     * @param  \App\Models\CalibrationPerform  $calibrationPerform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CalibrationPerform $calibrationPerform)
    {
        //
        // Check if the user has an Admin role
        if ($this->isAdmin($user)) {
            return true;
        }

        // Check if the user is a Manager and either requested or performed the maintenance
        if ($user->hasRole('Manager')) {
            return $calibrationPerform->requester_id == $user->id ||
                $calibrationPerform->performed_by_id == $user->id;
        }

        // Check if the user is a Technician and performed the maintenance
        if ($this->isTechnician($user)) {
            return $calibrationPerform->performed_by_id == $user->id;
        }

        // Return false if none of the above conditions are met
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalibrationPerform  $calibrationPerform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CalibrationPerform $calibrationPerform)
    {
        // Check if the maintenance perform status is not 'InProgress'
        // if ($calibrationPerform->status === 'InProgress') {
        //     return false;
        // }

        // Check if the user has an Admin role
        if ($this->isAdmin($user)) {
            return true;
        }

        // Check if the user is a Manager and either requested or performed the maintenance
        if ($user->hasRole('Manager')) {
            return $calibrationPerform->requester_id == $user->id ||
                $calibrationPerform->performed_by_id == $user->id;
        }

        // Check if the user is a Technician and performed the maintenance
        if ($this->isTechnician($user)) {
            return $calibrationPerform->performed_by_id == $user->id;
        }

        // Return false if none of the conditions are met
        return false;

        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalibrationPerform  $calibrationPerform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CalibrationPerform $calibrationPerform)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalibrationPerform  $calibrationPerform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CalibrationPerform $calibrationPerform)
    {
        //
    }
}