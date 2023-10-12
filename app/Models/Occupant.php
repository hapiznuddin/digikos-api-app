<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Occupant extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    /**
     * Get the user that owns the Occupant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the user associated with the Occupant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ktpDoc(): HasOne
    {
        return $this->hasOne(OcCitizenshipDoc::class, 'occupant_id', 'id');
    }

    public function familyDoc(): HasOne
    {
        return $this->hasOne(FamilyDoc::class, 'occupant_id', 'id');
    }

    public function profilePic(): HasOne
    {
        return $this->hasOne(ProfilePic::class, 'occupant_id', 'id');
    }

    public function testimonial(): HasMany
    {
        return $this->hasMany(Testimonial::class, 'occupants_id', 'id');
    }

    public function rooms(): HasOne
    {
        return $this->hasOne(Room::class, 'occupants_id', 'id');
    }

    public function rent(): HasMany
    {
        return $this->hasMany(Rent::class, 'occupants_id', 'id');
    }
}
