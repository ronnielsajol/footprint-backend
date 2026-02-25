<?php

namespace App\Policies;

use App\Models\AscDirective;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AscDirectivePolicy
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
    public function view(User $user, AscDirective $ascDirective): bool
    {
        // Superadmin can view all ASC directives
        if ($user->isSuperadmin()) {
            return true;
        }

        // POL admin can view directives for their own events
        return $user->id === $ascDirective->event->created_by;
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
    public function update(User $user, AscDirective $ascDirective): bool
    {
        // Superadmin can update all directives
        if ($user->isSuperadmin()) {
            return true;
        }

        // POL admin can update directives for their own events
        return $user->id === $ascDirective->event->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AscDirective $ascDirective): bool
    {
        // Superadmin can delete all directives
        if ($user->isSuperadmin()) {
            return true;
        }

        // POL admin can delete directives for their own events
        return $user->id === $ascDirective->event->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AscDirective $ascDirective): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AscDirective $ascDirective): bool
    {
        return false;
    }
}
