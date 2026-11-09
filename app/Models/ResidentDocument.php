<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidentDocument extends Model
{
    protected $fillable = [
        'resident_id', 'document_type', 'file_path', 'original_name', 'uploaded_by',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getDocumentTypeLabelAttribute(): string
    {
        return match ($this->document_type) {
            'national_id'    => 'National ID',
            'birth_cert'     => 'Birth Certificate',
            'voter_id'       => 'Voter ID',
            'philhealth'     => 'PhilHealth Card',
            'sss'            => 'SSS Card',
            'tin'            => 'TIN Card',
            'other'          => 'Other Document',
            default          => ucwords(str_replace('_', ' ', $this->document_type)),
        };
    }
}