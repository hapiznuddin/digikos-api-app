<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckInvoiceResource extends JsonResource
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
            'rent_id' => $this->rent_id,
            'room_id' => $this->rent->room->id,
            'occupant_id' => $this->rent->occupant->id,
            'invoice_date' => $this->invoice_date,
            'price' => $this->rent->room->room_price,
            'status' => $this->status,
        ];
    }
}
