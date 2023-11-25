<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintMessage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function rent(): BelongsTo
    {
        return $this->belongsTo(Rent::class, 'rent_id', 'id');
    }
}
