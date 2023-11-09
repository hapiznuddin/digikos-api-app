<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllClassRoomLandingPageResource extends JsonResource
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
            "id_facility" => $this->id_facility,
            "room_name" => $this->room_name,
            "room_description" => $this->room_description,
            "room_size" => $this->room_size,
            "room_price" => $this->room_price,
            "room_deposite" => $this->room_deposite,
            'avg_rating' => optional($this->statisticReview)->average_rating ?? 0,
            'image_room' => $this->firstImageRoom->path,
        ];
    }
}
