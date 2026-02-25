<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\Paginatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class WAscDeployment extends Model
{
  use HasFactory, SoftDeletes, Filterable, Paginatable;

  protected $table = 'w_asc_deployments';

  protected $fillable = [
    'exact_venue',
    'barangay',
    'city_municipality',
    'region',
    'district',
    'province',
    'deployment_month',
    'deployment_year',
    'exact_date',
    'event_tagging',
    'has_socials',
    'has_sortie',
    'asc_attended',
    'llc_attended',
    'psc_attended',
    'pol_activities',
    'sector',
    'remarks',
    'created_by',
  ];

  protected $casts = [
    'exact_date' => 'date',
    'deployment_month' => 'integer',
    'deployment_year' => 'integer',
    'has_socials' => 'boolean',
    'has_sortie' => 'boolean',
    'asc_attended' => 'boolean',
    'llc_attended' => 'boolean',
    'psc_attended' => 'boolean',
    'pol_activities' => 'array',
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
   * Get the officers for this deployment.
   */
  public function officers(): HasMany
  {
    return $this->hasMany(WAscDeploymentOfficer::class, 'w_asc_deployment_id');
  }

  /**
   * Get the VIPs associated with this deployment.
   */
  public function vips(): BelongsToMany
  {
    return $this->belongsToMany(Vip::class, 'w_asc_deployment_vips')
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
   * Scope: Search by venue, event tagging, barangay, city.
   */
  public function scopeSearch(Builder $query, string $search): Builder
  {
    return $query->where(function ($q) use ($search) {
      $q->where('exact_venue', 'like', "%{$search}%")
        ->orWhere('event_tagging', 'like', "%{$search}%")
        ->orWhere('barangay', 'like', "%{$search}%")
        ->orWhere('city_municipality', 'like', "%{$search}%")
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
   * Scope: Filter by sector.
   */
  public function scopeFilterBySector(Builder $query, string $sector): Builder
  {
    return $query->where('sector', $sector);
  }

  /**
   * Scope: Filter by has_socials flag.
   */
  public function scopeFilterByHasSocials(Builder $query, bool $hasSocials): Builder
  {
    return $query->where('has_socials', $hasSocials);
  }

  /**
   * Scope: Filter by has_sortie flag.
   */
  public function scopeFilterByHasSortie(Builder $query, bool $hasSortie): Builder
  {
    return $query->where('has_sortie', $hasSortie);
  }

  /**
   * Scope: Filter by asc_attended flag.
   */
  public function scopeFilterByAscAttended(Builder $query, bool $ascAttended): Builder
  {
    return $query->where('asc_attended', $ascAttended);
  }

  /**
   * Scope: Filter by llc_attended flag.
   */
  public function scopeFilterByLlcAttended(Builder $query, bool $llcAttended): Builder
  {
    return $query->where('llc_attended', $llcAttended);
  }

  /**
   * Scope: Filter by psc_attended flag.
   */
  public function scopeFilterByPscAttended(Builder $query, bool $pscAttended): Builder
  {
    return $query->where('psc_attended', $pscAttended);
  }
}
