<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purok extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'coordinates', 'color'];

    protected $casts = [
        'coordinates' => 'array',
    ];

    public function households()
    {
        return $this->hasMany(Household::class);
    }

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    public function getResidentCountAttribute(): int
    {
        return $this->residents()->where('status', 'active')->count();
    }
}