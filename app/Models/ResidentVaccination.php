<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentVaccination extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'vaccine_name',
        'vaccine_type',
        'dose_number',
        'date_administered',
        'notes',
    ];

    protected $casts = [
        'date_administered' => 'date',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
