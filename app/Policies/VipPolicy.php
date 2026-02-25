<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vip;
use Illuminate\Auth\Access\Response;

class VipPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Both superadmin and pol_admin can view VIPs
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vip $vip): bool
    {
        // All authenticated users can view VIP details
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Both superadmin and pol_admin can create VIPs
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vip $vip): bool
    {
        // Superadmin can update all VIPs
        if ($user->isSuperadmin()) {
            return true;
        }

        // POL admin can only update VIPs they created
        return $user->id === $vip->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vip $vip): bool
    {
        // Superadmin can delete all VIPs
        if ($user->isSuperadmin()) {
            return true;
        }

        // POL admin can only delete VIPs they created
        return $user->id === $vip->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Vip $vip): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Vip $vip): bool
    {
        return false;
    }
}
