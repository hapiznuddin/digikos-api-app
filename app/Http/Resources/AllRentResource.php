<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllRentResource extends JsonResource
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
            'price' => $this->total_price,
            'occupant_id' => $this->occupant->id,
            'name' => $this->occupant->name,
            'phone' => $this->occupant->phone,
            'address' => $this->occupant->address,
            'start_date' => $this->start_date,
            'room_id' => $this->room->id,
            'room' => [
                'number_room' => $this->room->number_room,
                'floor' => $this->room->number_floor
            ],
            'classroom_id' => $this->room->classroom->id,
            'classroom' => [
                'name' => $this->room->classroom->room_name,
                'size' => $this->room->classroom->room_size,
                'deposit' => $this->room->classroom->room_deposite
            ],
            'status_id' => $this->statusRent->id,
            'status' => $this->statusRent->status
        ];
    }
}
