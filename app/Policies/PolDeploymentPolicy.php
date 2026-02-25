<?php

namespace App\Policies;

use App\Models\PolDeployment;
use App\Models\User;

class PolDeploymentPolicy
{
  /**
   * Determine if the user can view any deployments.
   */
  public function viewAny(User $user): bool
  {
    return true; // Both superadmin and pol_admin can view
  }

  /**
   * Determine if the user can view the deployment.
   */
  public function view(User $user, PolDeployment $deployment): bool
  {
    return $user->isSuperadmin() || $deployment->created_by === $user->id;
  }

  /**
   * Determine if the user can create deployments.
   */
  public function create(User $user): bool
  {
    return true; // Both roles can create
  }

  /**
   * Determine if the user can update the deployment.
   */
  public function update(User $user, PolDeployment $deployment): bool
  {
    return $user->isSuperadmin() || $deployment->created_by === $user->id;
  }

  /**
   * Determine if the user can delete the deployment.
   */
  public function delete(User $user, PolDeployment $deployment): bool
  {
    return $user->isSuperadmin() || $deployment->created_by === $user->id;
  }
}
