<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportRentResource extends JsonResource
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
            'occupant' => $this->occupant->name ?? null,
            'room_name' => $this->room->classroom->room_name ?? null,
            'number_room' => $this->room->number_room ?? null,
            'floor' => $this->room->number_floor ?? null,
            'start_date' => $this->start_date ?? null,
        ];
    }
}
