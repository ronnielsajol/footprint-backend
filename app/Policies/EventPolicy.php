<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Both superadmin and pol_admin can view events
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        // Superadmin can view all events
        if ($user->isSuperadmin()) {
            return true;
        }

        // POL admin can only view their own events
        return $user->id === $event->created_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Both superadmin and pol_admin can create events
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        // Superadmin can update all events
        if ($user->isSuperadmin()) {
            return true;
        }

        // POL admin can only update their own events
        return $user->id === $event->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        // Superadmin can delete all events
        if ($user->isSuperadmin()) {
            return true;
        }

        // POL admin can only delete their own events
        return $user->id === $event->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        return false;
    }
}
