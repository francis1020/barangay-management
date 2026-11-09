<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalAssistance extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'assistance_type',
        'amount',
        'date_given',
        'provider',
        'notes',
    ];

    protected $casts = [
        'date_given' => 'date',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
