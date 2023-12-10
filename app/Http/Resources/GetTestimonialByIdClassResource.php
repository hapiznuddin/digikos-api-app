<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetTestimonialByIdClassResource extends JsonResource
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
            'rent_id' => $this->rent->id,
            'occupant_id' => $this->rent->occupant->id,
            'name' => $this->rent->occupant->name,
            'number_room' => $this->rent->room->number_room,
            'floor' => $this->rent->room->number_floor,
            'review' => $this->review,
            'rating' => $this->rating,
            'created_at' => $this->created_at,
            'profile_pic' => $this->user->profilePic->path ?? null
        ];
    }
}
