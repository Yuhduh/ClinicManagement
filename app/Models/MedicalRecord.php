<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'visit_date',
        'visit_reference',
        'chief_complaint',
        'diagnosis',
        'treatment',
        'clinical_notes',
        'blood_pressure',
        'temperature',
        'pulse',
        'weight',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'temperature' => 'decimal:1',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
