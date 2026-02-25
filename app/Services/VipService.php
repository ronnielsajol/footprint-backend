<?php

namespace App\Services;

use App\Models\User;
use App\Models\Vip;
use Illuminate\Pagination\LengthAwarePaginator;

class VipService
{
  /**
   * Get all VIPs with filters
   */
  public function getVips(array $filters = []): LengthAwarePaginator
  {
    $query = Vip::with('creator');

    // Apply search
    if (!empty($filters['search'])) {
      $query->search($filters['search']);
    }

    // Apply sorting
    $query->applySorting($filters['sort_by'] ?? 'created_at', $filters['sort_order'] ?? 'desc');

    return $query->paginate($filters['per_page'] ?? 15);
  }

  /**
   * Create a new VIP
   */
  public function createVip(User $user, array $data): Vip
  {
    $data['created_by'] = $user->id;

    return Vip::create($data);
  }

  /**
   * Update an existing VIP
   */
  public function updateVip(Vip $vip, array $data): Vip
  {
    $vip->update($data);
    return $vip->fresh();
  }

  /**
   * Delete a VIP
   */
  public function deleteVip(Vip $vip): bool
  {
    return $vip->delete();
  }

  /**
   * Get VIP by ID
   */
  public function getVipById(int $id): ?Vip
  {
    return Vip::with(['creator', 'events'])->find($id);
  }

  /**
   * Check if VIP exists by name or contact
   */
  public function checkVipExists(string $firstName, string $lastName, ?string $contactNumber = null): ?Vip
  {
    $query = Vip::where('first_name', $firstName)
      ->where('last_name', $lastName);

    if ($contactNumber) {
      $query->where('contact_number', $contactNumber);
    }

    return $query->first();
  }
}
