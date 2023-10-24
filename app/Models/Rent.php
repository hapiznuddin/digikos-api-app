<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rent extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function occupant(): BelongsTo
    {
        return $this->belongsTo(Occupant::class, 'occupant_id', 'id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function statusRent(): BelongsTo
    {
        return $this->belongsTo(StatusRent::class, 'status_id', 'id');
    }

    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class, 'rent_id', 'id');
    }
}
