<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $fillable = [
        'purok_id', 'head_resident_id', 'household_no', 'address',
        'ownership_status', 'housing_type', 'income_class', 'monthly_income',
    ];

    protected $casts = [
        'monthly_income' => 'decimal:2',
    ];

    public function purok()
    {
        return $this->belongsTo(Purok::class);
    }

    public function head()
    {
        return $this->belongsTo(Resident::class, 'head_resident_id');
    }

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    public function getMemberCountAttribute(): int
    {
        return $this->residents()->where('status', 'active')->count();
    }

    public function getDemographicsAttribute()
    {
        $residents = $this->residents()->where('status', 'active')->get();
        
        $kids = 0;
        $teens = 0;
        $adults = 0;
        $seniors = 0;
        
        $employment = [];
        $education = [];
        
        $special = [
            'pwd' => 0,
            'senior' => 0,
            'solo_parent' => 0,
            'beneficiary_4ps' => 0,
        ];

        foreach ($residents as $resident) {
            $age = $resident->age;
            if ($age < 13) $kids++;
            elseif ($age < 18) $teens++;
            elseif ($age < 60) $adults++;
            else $seniors++;
            
            if ($resident->employment_status) {
                $status = str_replace('_', ' ', ucfirst($resident->employment_status));
                $employment[$status] = ($employment[$status] ?? 0) + 1;
            }
            
            if ($resident->educational_attainment) {
                $level = $resident->educational_attainment;
                $education[$level] = ($education[$level] ?? 0) + 1;
            }
            
            if ($resident->is_pwd) $special['pwd']++;
            if ($resident->is_senior_citizen) $special['senior']++;
            if ($resident->is_solo_parent) $special['solo_parent']++;
            if ($resident->is_4ps_beneficiary) $special['beneficiary_4ps']++;
        }

        return [
            'age_groups' => [
                'Kids (0-12)' => $kids,
                'Teens (13-17)' => $teens,
                'Adults (18-59)' => $adults,
                'Seniors (60+)' => $seniors,
            ],
            'employment' => $employment,
            'education' => $education,
            'special' => $special,
            'total_members' => $residents->count(),
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Household $household) {
            if (empty($household->household_no)) {
                $last = static::max('id') ?? 0;
                $household->household_no = 'HH-' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}