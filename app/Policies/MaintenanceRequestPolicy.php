<?php

namespace App\Policies;

use App\Models\MaintenanceRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaintenanceRequestPolicy extends BacePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user) || $this->isManager($user) || $this->isTechnician($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return $this->isAdmin($user) || 
               ($this->isManager($user) && $this->isRequesterOrSignedTo($user, $maintenanceRequest)) || 
               ($this->isTechnician($user) && ($maintenanceRequest->signed_to_id == $user->id || $maintenanceRequest->assignments->contains('assigned_to_id', $user->id)));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->isAdmin($user) || $this->isManager($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return $this->isAdmin($user) || $user->id === $maintenanceRequest->requester_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return $this->isAdmin($user) || $maintenanceRequest->requester_id == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can forward the request.
     */
    public function forward(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return $maintenanceRequest->status === 'Pending' && 
               ($this->isAdmin($user) || ($this->isManager($user) && $maintenanceRequest->signed_to_id == $user->id));
    }

    /**
     * Determine whether the user can reply with perform.
     */
    public function replyWithPerform(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return $maintenanceRequest->status === 'Pending' && 
               ($this->isAdmin($user) || 
                ($this->isManager($user) && ($maintenanceRequest->signed_to_id == $user->id || $maintenanceRequest->requester_id == $user->id)) ||
                ($this->isTechnician($user) && ($maintenanceRequest->signed_to_id == $user->id || $maintenanceRequest->assignments->contains('assigned_to_id', $user->id))));
    }

 

    /**
     * Helper method to check if the user is the requester or signed to the maintenance request.
     */
    private function isRequesterOrSignedTo(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return $maintenanceRequest->requester_id == $user->id || $maintenanceRequest->signed_to_id == $user->id;
    }
}
