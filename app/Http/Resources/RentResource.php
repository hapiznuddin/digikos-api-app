<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RentResource extends JsonResource
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
            'payment_term' => $this->payment_term,
            'total_payment' => $this->total_payment,
            'room_id' => $this->room->id,
            'room' => [
                'number_room' => $this->room->number_room,
                'floor' => $this->room->number_floor
            ],
            'classroom_id' => $this->room->classroom->id,
            'classroom' => [
                'name' => $this->room->classroom->room_name,
                'deposit' => $this->room->classroom->room_deposite
            ],
            'image_id' => $this->room->classroom->firstImageRoom->id,
            'room_image' => [
                'path' => $this->room->classroom->firstImageRoom->path,
            ],
        ];
    }
}
