<?php

namespace App\Policies;

use App\Models\AscParticipation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AscParticipationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AscParticipation $ascParticipation): bool
    {
        // Superadmin can view all ASC participations
        if ($user->isSuperadmin()) {
            return true;
        }

        // POL admin can view participations for their own events
        return $user->id === $ascParticipation->event->created_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AscParticipation $ascParticipation): bool
    {
        // Superadmin can update all participations
        if ($user->isSuperadmin()) {
            return true;
        }

        // POL admin can update participations for their own events
        return $user->id === $ascParticipation->event->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AscParticipation $ascParticipation): bool
    {
        // Superadmin can delete all participations
        if ($user->isSuperadmin()) {
            return true;
        }

        // POL admin can delete participations for their own events
        return $user->id === $ascParticipation->event->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AscParticipation $ascParticipation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AscParticipation $ascParticipation): bool
    {
        return false;
    }
}
