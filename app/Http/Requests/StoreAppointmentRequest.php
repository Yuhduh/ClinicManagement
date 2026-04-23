<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['doctor', 'receptionist'], true);
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'doctor'))],
            'appointment_date' => ['required', 'date'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'type' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['confirmed', 'pending', 'cancelled'])],
            'notes' => ['nullable', 'string'],
        ];
    }
}
