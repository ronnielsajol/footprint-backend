<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class AdminService
{
  /**
   * Get all POL admins (superadmin only)
   */
  public function getAdmins(array $filters = []): LengthAwarePaginator
  {
    $query = User::where('role', 'pol_admin');

    // Apply search
    if (!empty($filters['search'])) {
      $query->where(function ($q) use ($filters) {
        $q->where('name', 'like', "%{$filters['search']}%")
          ->orWhere('email', 'like', "%{$filters['search']}%");
      });
    }

    $query->orderBy('created_at', 'desc');

    $perPage = $filters['per_page'] ?? 15;
    return $query->paginate(min($perPage, 100));
  }

  /**
   * Create a new POL admin
   */
  public function createAdmin(array $data): User
  {
    $data['role'] = 'pol_admin';
    $data['password'] = Hash::make($data['password']);

    return User::create($data);
  }

  /**
   * Update an existing admin
   */
  public function updateAdmin(User $admin, array $data): User
  {
    if (isset($data['password'])) {
      $data['password'] = Hash::make($data['password']);
    }

    $admin->update($data);
    return $admin->fresh();
  }

  /**
   * Delete an admin
   */
  public function deleteAdmin(User $admin): bool
  {
    // Prevent deleting superadmin
    if ($admin->isSuperadmin()) {
      return false;
    }

    return $admin->delete();
  }

  /**
   * Get admin by ID
   */
  public function getAdminById(int $id): ?User
  {
    return User::where('role', 'pol_admin')->find($id);
  }
}
