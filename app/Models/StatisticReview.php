<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatisticReview extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'id_class_room', 'id');
    }
}
