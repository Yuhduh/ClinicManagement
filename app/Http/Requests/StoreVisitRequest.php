<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'doctor';
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'visit_date' => ['required', 'date'],
            'visit_reference' => ['nullable', 'string', 'max:255', 'unique:medical_records,visit_reference'],
            'chief_complaint' => ['nullable', 'string'],
            'diagnosis' => ['nullable', 'string'],
            'treatment' => ['nullable', 'string'],
            'clinical_notes' => ['nullable', 'string'],
            'blood_pressure' => ['nullable', 'string', 'max:20'],
            'temperature' => ['nullable', 'numeric'],
            'pulse' => ['nullable', 'integer'],
            'weight' => ['nullable', 'integer'],
        ];
    }
}
