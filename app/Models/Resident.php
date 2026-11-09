<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'household_id', 'purok_id', 'first_name', 'middle_name', 'last_name',
        'suffix', 'birthdate', 'birthplace', 'sex', 'civil_status', 'citizenship',
        'religion', 'occupation', 'employment_status', 'educational_attainment',
        'contact_number', 'email', 'philhealth_no', 'sss_no', 'tin_no',
        'status', 'is_4ps_beneficiary', 'is_pwd', 'is_senior_citizen',
        'is_solo_parent', 'profile_photo',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'is_4ps_beneficiary' => 'boolean',
        'is_pwd' => 'boolean',
        'is_senior_citizen' => 'boolean',
        'is_solo_parent' => 'boolean',
    ];

    // Relationships
    public function household() { return $this->belongsTo(Household::class); }
    public function purok()     { return $this->belongsTo(Purok::class); }
    public function familyMembers() { return $this->hasMany(FamilyMember::class); }
    public function documents() { return $this->hasMany(ResidentDocument::class); }
    public function voterInfo() { return $this->hasOne(VoterInfo::class); }
    public function healthRecord() { return $this->hasOne(ResidentHealthRecord::class); }
    public function vaccinations() { return $this->hasMany(ResidentVaccination::class); }
    public function medicalAssistances() { return $this->hasMany(MedicalAssistance::class); }

    // Computed attributes
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name} {$this->suffix}");
    }

    public function getAgeAttribute(): int
    {
        return $this->birthdate->age;
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->profile_photo
            ? asset('storage/' . $this->profile_photo)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=7F9CF5&background=EBF4FF';
    }

    // Scopes for filtering
    public function scopeByPurok($query, $purokId)   { return $query->where('purok_id', $purokId); }
    public function scopeVoters($query)               { return $query->whereHas('voterInfo', fn($q) => $q->where('is_registered', true)); }
    public function scopeSeniorCitizens($query)       { return $query->where('is_senior_citizen', true); }
    public function scopeActive($query)               { return $query->where('status', 'active'); }
    public function scopeSearch($query, $term)
    {
        return $query->where(fn($q) => $q
            ->where('first_name', 'like', "%{$term}%")
            ->orWhere('last_name', 'like', "%{$term}%")
            ->orWhere('contact_number', 'like', "%{$term}%")
        );
    }
}