<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailRentResource extends JsonResource
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
            'start_date' => $this->start_date,
            'total_price' => $this->total_price,
            'additional_occupant' => $this->additional_occupant,
            'occupant_id' => $this->occupant->id,
            'occupant' => [
                'name' => $this->occupant->name,
                'phone' => $this->occupant->phone,
                'address'=> $this->occupant->address,
                'gender' => $this->occupant->gender,
                'occupation' => $this->occupant->occupation,
            ],
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
