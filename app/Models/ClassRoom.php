<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassRoom extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'id_facility', 'id');
    }

    public function rooms():HasMany
    {
        return $this->hasMany(Room::class, 'id_class_room', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class, 'id_class_room', 'id');
    }
}
