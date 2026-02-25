<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VipResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'birth_date' => $this->birth_date->format('Y-m-d'),
            'created_by' => [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
            ],
            'events_count' => $this->whenCounted('events'),
            'pivot' => $this->when($this->pivot, function () {
                return [
                    'remarks' => $this->pivot->remarks,
                ];
            }),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
