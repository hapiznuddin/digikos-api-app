<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OccupantDetailResource extends JsonResource
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
            'user_id' => $this->user_id,
            'name' => $this->user->name,
            'date_birth' => $this->date_birth,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'address' => $this->address,
            'occupation' => $this->occupation
            // 'ktp_doc' => [
            //     'original_name' => $this->ktpDoc->original_name,
            //     'path' => $this->ktpDoc->path,
            // ]
        ];
    }
}
