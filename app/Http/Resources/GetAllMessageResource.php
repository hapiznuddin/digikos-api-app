<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetAllMessageResource extends JsonResource
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
            'name' => $this->rent->occupant->name,
            'room_name' => $this->rent->room->classRoom->room_name,
            'number' => $this->rent->room->number_room,
            'floor' => $this->rent->room->number_floor,
            'created_at' => $this->created_at->format('Y-m-d'),
            'message' => $this->message,
            'status' => $this->status,
        ];
    }
}
