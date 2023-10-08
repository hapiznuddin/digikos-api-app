<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function classRoom(): BelongsTo
    {
        return $this-> belongsTo(ClassRoom::class, 'id_class_room', 'id');
    }

    public function occupant(): BelongsTo
    {
        return $this->belongsTo(Occupant::class, 'occupants_id', 'id');
    }

    public function testimonial(): HasMany
    {
        return $this->hasMany(Testimonial::class, 'room_id', 'id');
    }

    public function rent(): HasMany
    {
        return $this->hasMany(Rent::class, 'room_id', 'id');
    }
}
