<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusRent extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function rent():HasMany
    {
        return $this->hasMany(Rent::class, 'status_id', 'id');
    }
}
