<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function room():BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function occupant():BelongsTo
    {
        return $this->belongsTo(Occupant::class, 'occupant_id');
    }

    public function rent():BelongsTo
    {
        return $this->belongsTo(Rent::class, 'rent_id');
    }
}
