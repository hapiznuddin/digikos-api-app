<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetAllInvoiceResource extends JsonResource
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
            'start_date' => $this->rent->start_date,
            'invoice_date' => $this->invoice_date,
            'price' => $this->rent->room->room_price,
            'status' => $this->status,
        ];
    }
}
