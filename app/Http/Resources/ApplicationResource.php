<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
        [
            'id' => $this->id,
            'job' => new JobResource($this->whenLoaded('job')),
            'user' => new UserResource($this->whenLoaded('user')),
            'resume' => new ResumeResource($this->whenLoaded('resume')),
            'cover_letter' => $this->cover_letter,
            'status' => $this->status,
        ];
    }
}
