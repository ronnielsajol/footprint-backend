<?php

namespace App\Services;

use App\Models\WAscDeployment;
use App\Models\WAscDeploymentOfficer;
use App\Models\User;
use App\Models\Vip;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class WAscDeploymentService
{
  /**
   * Get W ASC deployments with filters.
   */
  public function getDeployments(User $user, array $filters): LengthAwarePaginator
  {
    $query = WAscDeployment::query()->forUser($user);

    // Apply search
    if (!empty($filters['search'])) {
      $query->search($filters['search']);
    }

    // Apply year filter
    if (!empty($filters['year'])) {
      $query->filterByYear((int) $filters['year']);
    }

    // Apply month filter
    if (!empty($filters['month'])) {
      $query->filterByMonth((int) $filters['month']);
    }

    // Apply sector filter
    if (!empty($filters['sector'])) {
      $query->filterBySector($filters['sector']);
    }

    // Apply boolean filters
    if (isset($filters['has_socials'])) {
      $query->filterByHasSocials((bool) $filters['has_socials']);
    }

    if (isset($filters['has_sortie'])) {
      $query->filterByHasSortie((bool) $filters['has_sortie']);
    }

    if (isset($filters['asc_attended'])) {
      $query->filterByAscAttended((bool) $filters['asc_attended']);
    }

    if (isset($filters['llc_attended'])) {
      $query->filterByLlcAttended((bool) $filters['llc_attended']);
    }

    if (isset($filters['psc_attended'])) {
      $query->filterByPscAttended((bool) $filters['psc_attended']);
    }

    // Apply sorting
    $sortBy = $filters['sort_by'] ?? 'created_at';
    $sortOrder = $filters['sort_order'] ?? 'desc';
    $query->orderBy($sortBy, $sortOrder);

    // Paginate
    $perPage = $filters['per_page'] ?? 15;
    return $query->paginated((int) $perPage);
  }

  /**
   * Create a new W ASC deployment.
   */
  public function createDeployment(User $user, array $data): WAscDeployment
  {
    $data['created_by'] = $user->id;
    return WAscDeployment::create($data);
  }

  /**
   * Update a W ASC deployment.
   */
  public function updateDeployment(WAscDeployment $deployment, array $data): WAscDeployment
  {
    $deployment->update($data);
    return $deployment->fresh();
  }

  /**
   * Delete a W ASC deployment (soft delete).
   */
  public function deleteDeployment(WAscDeployment $deployment): bool
  {
    return $deployment->delete();
  }

  /**
   * Get a W ASC deployment by ID.
   */
  public function getDeploymentById(int $id): ?WAscDeployment
  {
    return WAscDeployment::with(['creator', 'officers', 'vips', 'ascDirectives', 'ascParticipations'])->find($id);
  }

  /**
   * Add an officer to a W ASC deployment.
   */
  public function addOfficerToDeployment(WAscDeployment $deployment, string $officerName): WAscDeploymentOfficer
  {
    return $deployment->officers()->create(['officer_name' => $officerName]);
  }

  /**
   * Update an officer.
   */
  public function updateOfficer(WAscDeploymentOfficer $officer, string $officerName): WAscDeploymentOfficer
  {
    $officer->update(['officer_name' => $officerName]);
    return $officer->fresh();
  }

  /**
   * Remove an officer from deployment.
   */
  public function removeOfficer(WAscDeploymentOfficer $officer): bool
  {
    return $officer->delete();
  }

  /**
   * Get officers for a deployment.
   */
  public function getDeploymentOfficers(WAscDeployment $deployment): Collection
  {
    return $deployment->officers;
  }

  /**
   * Add a VIP to a W ASC deployment.
   */
  public function addVipToDeployment(WAscDeployment $deployment, Vip $vip, ?string $remarks = null): void
  {
    if (!$deployment->vips()->where('vip_id', $vip->id)->exists()) {
      $deployment->vips()->attach($vip->id, ['remarks' => $remarks]);
    }
  }

  /**
   * Remove a VIP from a W ASC deployment.
   */
  public function removeVipFromDeployment(WAscDeployment $deployment, int $vipId): bool
  {
    return $deployment->vips()->detach($vipId) > 0;
  }

  /**
   * Get VIPs for a W ASC deployment.
   */
  public function getDeploymentVips(WAscDeployment $deployment): Collection
  {
    return $deployment->vips;
  }
}
