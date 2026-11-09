<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoterInfo extends Model
{
    protected $fillable = [
        'resident_id', 'precinct_no', 'voters_id_no',
        'is_registered', 'registration_date', 'voting_center',
    ];

    protected $casts = [
        'is_registered'     => 'boolean',
        'registration_date' => 'date',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}