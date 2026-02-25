<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PolDeploymentResource extends JsonResource
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
      'event_name' => $this->event_name,
      'exact_venue' => $this->exact_venue,
      'lgu' => $this->lgu,
      'barangay' => $this->barangay,
      'region' => $this->region,
      'district' => $this->district,
      'province' => $this->province,
      'deployment_month' => $this->deployment_month,
      'deployment_year' => $this->deployment_year,
      'turnover_date' => $this->turnover_date?->format('Y-m-d'),
      'pol_officer' => $this->pol_officer,
      'category' => $this->category,
      'asc_type' => $this->asc_type,
      'llc' => $this->llc,
      'psc' => $this->psc,
      'proponent' => $this->proponent,
      'sector_recipient' => $this->sector_recipient,
      'count' => $this->count,
      'unit' => $this->unit,
      'donation_summary' => $this->donation_summary,
      'amount' => $this->amount,
      'source' => $this->source,
      'remarks' => $this->remarks,
      'created_by' => $this->created_by,
      'created_at' => $this->created_at?->toISOString(),
      'updated_at' => $this->updated_at?->toISOString(),

      // Relationships (when loaded)
      'creator' => new UserResource($this->whenLoaded('creator')),
      'vips' => VipResource::collection($this->whenLoaded('vips')),
      'asc_directives' => AscDirectiveResource::collection($this->whenLoaded('ascDirectives')),
      'asc_participations' => AscParticipationResource::collection($this->whenLoaded('ascParticipations')),
    ];
  }
}
