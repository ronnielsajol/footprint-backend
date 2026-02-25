<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WAscDeploymentOfficerResource extends JsonResource
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
      'w_asc_deployment_id' => $this->w_asc_deployment_id,
      'officer_name' => $this->officer_name,
      'created_at' => $this->created_at?->toISOString(),
      'updated_at' => $this->updated_at?->toISOString(),
    ];
  }
}
