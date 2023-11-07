<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetRentHistoryByRoomIdResource extends JsonResource
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
            'endt_date' => $this->end_date,
            'occupant_id' => $this->occupant ? $this->occupant->id : null,
            'occupant' => [
                'name' => $this->occupant->name,
                'phone' => $this->occupant->phone,
                'address'=> $this->occupant->address,
            ],
        ];
    }
}
