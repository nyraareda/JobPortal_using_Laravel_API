<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'industry' => $this->industry,
            'description' => $this->description,
            'location' => $this->location,
            'jobs' => JobResource::collection($this->whenLoaded('jobs')),
        ];
    }
}
