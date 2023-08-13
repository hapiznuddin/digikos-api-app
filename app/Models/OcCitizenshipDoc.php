<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OcCitizenshipDoc extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    /**
     * Get the user that owns the OcCitizenshipDoc
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function occupant(): BelongsTo
    {
        return $this->belongsTo(Occupant::class, 'occupant_id', 'id');
    }
}


