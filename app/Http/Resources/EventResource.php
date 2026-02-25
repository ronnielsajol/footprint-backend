<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'title' => $this->title,
            'event_type' => $this->event_type,
            'description' => $this->description,
            'event_date' => $this->event_date->format('Y-m-d'),
            'location' => $this->location,
            'status' => $this->status,
            'created_by' => [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
                'email' => $this->creator->email,
            ],
            'vips_count' => $this->whenCounted('vips'),
            'vips' => VipResource::collection($this->whenLoaded('vips')),
            'asc_directives' => AscDirectiveResource::collection($this->whenLoaded('ascDirectives')),
            'asc_participations' => AscParticipationResource::collection($this->whenLoaded('ascParticipations')),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
