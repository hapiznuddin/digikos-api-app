<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClassRoom extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'id_facility', 'id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'id_class_room', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class, 'id_class_room', 'id');
    }

    public function firstImageRoom(): HasOne
    {
        return $this->hasOne(RoomImage::class, 'id_class_room', 'id')->orderBy('id', 'asc');
    }

    public function testimonial(): HasMany
    {
        return $this->hasMany(Testimonial::class, 'id_class_room', 'id');
    }
}
