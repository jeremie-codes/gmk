<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Relations
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }
}
