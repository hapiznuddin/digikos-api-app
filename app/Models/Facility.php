<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function classRoom(): HasMany
    {
        return $this->hasMany(ClassRoom::class, 'id_facility', 'id');
    }
}
