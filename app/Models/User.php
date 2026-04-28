<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['first_name', 'last_name', 'middle_initial', 'email', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user's display name in format: Last Name, First Name Middle Initial
     */
    public function getDisplayNameAttribute(): string
    {
        $parts = [];

        if ($this->last_name) {
            $parts[] = $this->last_name;
        }

        if ($this->first_name) {
            $parts[] = $this->first_name;
        }

        if ($this->middle_initial) {
            $parts[] = $this->middle_initial;
        }

        return implode(' ', $parts) ?: 'N/A';
    }

    public function getRedirectRoute(): string
    {
        return 'dashboard';
    }

    public function doctorAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function createdAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'created_by');
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class, 'doctor_id');
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class, 'doctor_id');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(MedicalRecord::class, 'doctor_id');
    }
}
