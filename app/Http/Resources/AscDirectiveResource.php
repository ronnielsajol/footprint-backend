<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AscDirectiveResource extends JsonResource
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
            'event_id' => $this->event_id,
            'directive_text' => $this->directive_text,
            'issued_by' => $this->issued_by,
            'issued_date' => $this->issued_date->format('Y-m-d'),
            'created_by' => [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
            ],
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
