<?php

namespace App\Traits;

trait Filterable
{
  /**
   * Apply filters to the query based on request parameters
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @param array $filters
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeApplyFilters($query, array $filters = [])
  {
    foreach ($filters as $key => $value) {
      if (is_null($value) || $value === '') {
        continue;
      }

      // Handle different filter types
      if (str_ends_with($key, '_from') || str_ends_with($key, '_start')) {
        $field = str_replace(['_from', '_start'], '', $key);
        $query->whereDate($field, '>=', $value);
      } elseif (str_ends_with($key, '_to') || str_ends_with($key, '_end')) {
        $field = str_replace(['_to', '_end'], '', $key);
        $query->whereDate($field, '<=', $value);
      } elseif (str_ends_with($key, '_like')) {
        $field = str_replace('_like', '', $key);
        $query->where($field, 'like', "%{$value}%");
      } else {
        $query->where($key, $value);
      }
    }

    return $query;
  }

  /**
   * Apply sorting to the query
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @param string $sortBy
   * @param string $sortOrder
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeApplySorting($query, $sortBy = 'created_at', $sortOrder = 'desc')
  {
    $sortBy = request('sort_by', $sortBy);
    $sortOrder = request('sort_order', $sortOrder);

    // Validate sort order
    $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';

    return $query->orderBy($sortBy, $sortOrder);
  }
}
