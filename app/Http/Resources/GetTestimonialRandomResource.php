<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetTestimonialRandomResource extends JsonResource
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
            'user_id' => $this->user->id,
            'occupant_id' => $this->rent->occupant->id,
            'name' => $this->rent->occupant->name,
            'review' => $this->review,
            'profile_pic' => $this->user->profilePic->path
        ];
    }
}
