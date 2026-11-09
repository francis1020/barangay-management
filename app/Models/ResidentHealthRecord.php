<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentHealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'blood_type',
        'height',
        'weight',
        'allergies',
        'medical_conditions',
        'medications',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
