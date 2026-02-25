<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\Paginatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class PolDeployment extends Model
{
  use HasFactory, SoftDeletes, Filterable, Paginatable;

  protected $fillable = [
    'event_name',
    'exact_venue',
    'lgu',
    'barangay',
    'region',
    'district',
    'province',
    'deployment_month',
    'deployment_year',
    'turnover_date',
    'pol_officer',
    'category',
    'asc_type',
    'llc',
    'psc',
    'proponent',
    'sector_recipient',
    'count',
    'unit',
    'donation_summary',
    'amount',
    'source',
    'remarks',
    'created_by',
  ];

  protected $casts = [
    'turnover_date' => 'date',
    'deployment_month' => 'integer',
    'deployment_year' => 'integer',
    'count' => 'integer',
    'amount' => 'decimal:2',
    'deleted_at' => 'datetime',
  ];

  /**
   * Get the user who created this deployment.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the VIPs associated with this deployment.
   */
  public function vips(): BelongsToMany
  {
    return $this->belongsToMany(Vip::class, 'pol_deployment_vips')
      ->withPivot('remarks')
      ->withTimestamps();
  }

  /**
   * Get the ASC directives for this deployment (polymorphic).
   */
  public function ascDirectives(): MorphMany
  {
    return $this->morphMany(AscDirective::class, 'deployment');
  }

  /**
   * Get the ASC participations for this deployment (polymorphic).
   */
  public function ascParticipations(): MorphMany
  {
    return $this->morphMany(AscParticipation::class, 'deployment');
  }

  /**
   * Scope: Filter by user role (superadmin sees all, pol_admin sees own).
   */
  public function scopeForUser(Builder $query, User $user): Builder
  {
    if ($user->isSuperadmin()) {
      return $query;
    }

    return $query->where('created_by', $user->id);
  }

  /**
   * Scope: Filter by creator.
   */
  public function scopeByCreator(Builder $query, int $creatorId): Builder
  {
    return $query->where('created_by', $creatorId);
  }

  /**
   * Scope: Search by event name, venue, officer, category.
   */
  public function scopeSearch(Builder $query, string $search): Builder
  {
    return $query->where(function ($q) use ($search) {
      $q->where('event_name', 'like', "%{$search}%")
        ->orWhere('exact_venue', 'like', "%{$search}%")
        ->orWhere('pol_officer', 'like', "%{$search}%")
        ->orWhere('category', 'like', "%{$search}%")
        ->orWhere('lgu', 'like', "%{$search}%")
        ->orWhere('barangay', 'like', "%{$search}%")
        ->orWhere('province', 'like', "%{$search}%");
    });
  }

  /**
   * Scope: Filter by year.
   */
  public function scopeFilterByYear(Builder $query, int $year): Builder
  {
    return $query->where('deployment_year', $year);
  }

  /**
   * Scope: Filter by month.
   */
  public function scopeFilterByMonth(Builder $query, int $month): Builder
  {
    return $query->where('deployment_month', $month);
  }

  /**
   * Scope: Filter by source.
   */
  public function scopeFilterBySource(Builder $query, string $source): Builder
  {
    return $query->where('source', $source);
  }

  /**
   * Scope: Filter by category.
   */
  public function scopeFilterByCategory(Builder $query, string $category): Builder
  {
    return $query->where('category', 'like', "%{$category}%");
  }

  /**
   * Scope: Filter by ASC type.
   */
  public function scopeFilterByAscType(Builder $query, string $ascType): Builder
  {
    return $query->where('asc_type', $ascType);
  }
}
