<?php

namespace App\Traits;

trait Paginatable
{
  /**
   * Apply pagination to the query
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @param int $perPage
   * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
   */
  public function scopePaginated($query, $perPage = 15)
  {
    $perPage = request('per_page', $perPage);

    // Limit per_page to prevent excessive loads
    $perPage = min($perPage, 100);

    return $query->paginate($perPage);
  }
}
