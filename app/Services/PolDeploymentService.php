<?php

namespace App\Services;

use App\Models\PolDeployment;
use App\Models\User;
use App\Models\Vip;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PolDeploymentService
{
  /**
   * Get pol deployments with filters.
   */
  public function getDeployments(User $user, array $filters): LengthAwarePaginator
  {
    $query = PolDeployment::query()->forUser($user);

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

    // Apply source filter
    if (!empty($filters['source'])) {
      $query->filterBySource($filters['source']);
    }

    // Apply category filter
    if (!empty($filters['category'])) {
      $query->filterByCategory($filters['category']);
    }

    // Apply ASC type filter
    if (!empty($filters['asc_type'])) {
      $query->filterByAscType($filters['asc_type']);
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
   * Create a new POL deployment.
   */
  public function createDeployment(User $user, array $data): PolDeployment
  {
    $data['created_by'] = $user->id;
    return PolDeployment::create($data);
  }

  /**
   * Update a POL deployment.
   */
  public function updateDeployment(PolDeployment $deployment, array $data): PolDeployment
  {
    $deployment->update($data);
    return $deployment->fresh();
  }

  /**
   * Delete a POL deployment (soft delete).
   */
  public function deleteDeployment(PolDeployment $deployment): bool
  {
    return $deployment->delete();
  }

  /**
   * Get a POL deployment by ID.
   */
  public function getDeploymentById(int $id): ?PolDeployment
  {
    return PolDeployment::with(['creator', 'vips', 'ascDirectives', 'ascParticipations'])->find($id);
  }

  /**
   * Add a VIP to a POL deployment.
   */
  public function addVipToDeployment(PolDeployment $deployment, Vip $vip, ?string $remarks = null): void
  {
    if (!$deployment->vips()->where('vip_id', $vip->id)->exists()) {
      $deployment->vips()->attach($vip->id, ['remarks' => $remarks]);
    }
  }

  /**
   * Remove a VIP from a POL deployment.
   */
  public function removeVipFromDeployment(PolDeployment $deployment, int $vipId): bool
  {
    return $deployment->vips()->detach($vipId) > 0;
  }

  /**
   * Get VIPs for a POL deployment.
   */
  public function getDeploymentVips(PolDeployment $deployment): Collection
  {
    return $deployment->vips;
  }
}
