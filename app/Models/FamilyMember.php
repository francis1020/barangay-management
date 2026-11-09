<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    protected $fillable = [
        'resident_id', 'related_resident_id', 'relationship',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function relatedResident()
    {
        return $this->belongsTo(Resident::class, 'related_resident_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->relatedResident ? $this->relatedResident->full_name : 'Unknown';
    }
}