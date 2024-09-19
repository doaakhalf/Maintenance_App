<?php

namespace App\Policies;

use App\Models\CalibrationRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalibrationRequestPolicy extends BacePolicy
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
        return $this->isAdmin($user) || $this->isManager($user) || $this->isTechnician($user);

    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalibrationRequest  $calibrationRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CalibrationRequest $calibrationRequest)
    {
        return $this->isAdmin($user) || 
               ($this->isManager($user) && $this->isRequesterOrSignedTo($user, $calibrationRequest)) || 
               ($this->isTechnician($user) && ($calibrationRequest->signed_to_id == $user->id || $calibrationRequest->assignments->contains('assigned_to_id', $user->id)));
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
        return $this->isAdmin($user) || $this->isManager($user);

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalibrationRequest  $calibrationRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CalibrationRequest $calibrationRequest)
    {
        //
        return $this->isAdmin($user) || $user->id === $calibrationRequest->requester_id;

    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalibrationRequest  $calibrationRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CalibrationRequest $calibrationRequest)
    {
        //
        return $this->isAdmin($user) || $calibrationRequest->requester_id == $user->id;

    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalibrationRequest  $calibrationRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CalibrationRequest $calibrationRequest)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CalibrationRequest  $calibrationRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CalibrationRequest $calibrationRequest)
    {
        //
    }

     /**
     * Determine whether the user can forward the request.
     */
    public function forward(User $user, CalibrationRequest $calibrationRequest): bool
    {
        return $calibrationRequest->status === 'Pending' && 
               ($this->isAdmin($user) || ($this->isManager($user) && $calibrationRequest->signed_to_id == $user->id));
    }

    /**
     * Determine whether the user can reply with perform.
     */
    public function replyWithPerform(User $user, CalibrationRequest $calibrationRequest): bool
    {
        return $calibrationRequest->status === 'Pending' && 
               ($this->isAdmin($user) || 
                ($this->isManager($user) && ($calibrationRequest->signed_to_id == $user->id || $calibrationRequest->requester_id == $user->id)) ||
                ($this->isTechnician($user) && ($calibrationRequest->signed_to_id == $user->id || $calibrationRequest->assignments->contains('assigned_to_id', $user->id))));
    }

 

    /**
     * Helper method to check if the user is the requester or signed to the maintenance request.
     */
    private function isRequesterOrSignedTo(User $user, CalibrationRequest $calibrationRequest): bool
    {
        return $calibrationRequest->requester_id == $user->id || $calibrationRequest->signed_to_id == $user->id;
    }
}
