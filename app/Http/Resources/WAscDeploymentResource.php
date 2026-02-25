<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WAscDeploymentResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'exact_venue' => $this->exact_venue,
      'barangay' => $this->barangay,
      'city_municipality' => $this->city_municipality,
      'region' => $this->region,
      'district' => $this->district,
      'province' => $this->province,
      'deployment_month' => $this->deployment_month,
      'deployment_year' => $this->deployment_year,
      'exact_date' => $this->exact_date?->format('Y-m-d'),
      'event_tagging' => $this->event_tagging,
      'has_socials' => $this->has_socials,
      'has_sortie' => $this->has_sortie,
      'asc_attended' => $this->asc_attended,
      'llc_attended' => $this->llc_attended,
      'psc_attended' => $this->psc_attended,
      'pol_activities' => $this->pol_activities,
      'sector' => $this->sector,
      'remarks' => $this->remarks,
      'created_by' => $this->created_by,
      'created_at' => $this->created_at?->toISOString(),
      'updated_at' => $this->updated_at?->toISOString(),

      // Relationships (when loaded)
      'creator' => new UserResource($this->whenLoaded('creator')),
      'officers' => WAscDeploymentOfficerResource::collection($this->whenLoaded('officers')),
      'vips' => VipResource::collection($this->whenLoaded('vips')),
      'asc_directives' => AscDirectiveResource::collection($this->whenLoaded('ascDirectives')),
      'asc_participations' => AscParticipationResource::collection($this->whenLoaded('ascParticipations')),
    ];
  }
}
