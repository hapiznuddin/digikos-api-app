<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilePic extends Model
{
    use HasFactory;
    public $timestamps = false;

    public $guarded = [];

    public function occupant(): BelongsTo
    {
        return $this->belongsTo(Occupant::class, 'occupant_id', 'id');
    }
}
