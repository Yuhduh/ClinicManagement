<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClinicManagementController extends Controller
{
    public function storePatient(Request $request): RedirectResponse
    {
        $request->validate([
            'patient_code' => ['required', 'string', 'max:50', 'unique:patients,patient_code'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
        ]);

        Patient::create($request->only([
            'patient_code',
            'first_name',
            'last_name',
            'email',
            'phone',
            'date_of_birth',
            'gender',
            'address',
        ]));

        return $this->redirectBack($request);
    }

    public function updatePatient(Request $request, Patient $patient): RedirectResponse
    {
        $request->validate([
            'patient_code' => ['required', 'string', 'max:50', Rule::unique('patients', 'patient_code')->ignore($patient->id)],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
        ]);

        $patient->update($request->only([
            'patient_code',
            'first_name',
            'last_name',
            'email',
            'phone',
            'date_of_birth',
            'gender',
            'address',
        ]));

        return $this->redirectBack($request);
    }

    public function destroyPatient(Request $request, Patient $patient): RedirectResponse
    {
        $patient->delete();

        return $this->redirectBack($request);
    }

    public function storeAppointment(Request $request): RedirectResponse
    {
        $data = $this->appointmentData($request);
        Appointment::create($data);

        return $this->redirectBack($request);
    }

    public function updateAppointment(Request $request, Appointment $appointment): RedirectResponse
    {
        $data = $this->appointmentData($request, $appointment);
        $appointment->update($data);

        return $this->redirectBack($request);
    }

    public function destroyAppointment(Request $request, Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return $this->redirectBack($request);
    }

    public function storePrescription(Request $request): RedirectResponse
    {
        $data = $this->prescriptionData($request);
        Prescription::create($data);

        return $this->redirectBack($request);
    }

    public function updatePrescription(Request $request, Prescription $prescription): RedirectResponse
    {
        $data = $this->prescriptionData($request, $prescription);
        $prescription->update($data);

        return $this->redirectBack($request);
    }

    public function destroyPrescription(Request $request, Prescription $prescription): RedirectResponse
    {
        $prescription->delete();

        return $this->redirectBack($request);
    }

    public function storeMedicalRecord(Request $request): RedirectResponse
    {
        $data = $this->medicalRecordData($request);
        MedicalRecord::create($data);

        return $this->redirectBack($request);
    }

    public function updateMedicalRecord(Request $request, MedicalRecord $medicalRecord): RedirectResponse
    {
        $data = $this->medicalRecordData($request, $medicalRecord);
        $medicalRecord->update($data);

        return $this->redirectBack($request);
    }

    public function destroyMedicalRecord(Request $request, MedicalRecord $medicalRecord): RedirectResponse
    {
        $medicalRecord->delete();

        return $this->redirectBack($request);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $data = $this->userData($request);
        User::create($data);

        return $this->redirectBack($request);
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $this->userData($request, $user);
        $user->update($data);

        return $this->redirectBack($request);
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        $user->delete();

        return $this->redirectBack($request);
    }

    private function appointmentData(Request $request, ?Appointment $appointment = null): array
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => $request->user()->role === 'doctor' ? ['nullable', 'exists:users,id'] : ['required', 'exists:users,id'],
            'scheduled_at' => ['required', 'date'],
            'type' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['confirmed', 'pending', 'cancelled'])],
            'notes' => ['nullable', 'string'],
        ]);

        $doctorId = $request->user()->role === 'doctor'
            ? $request->user()->id
            : (int) $validated['doctor_id'];

        return [
            'patient_id' => (int) $validated['patient_id'],
            'doctor_id' => $doctorId,
            'created_by' => $appointment?->created_by ?? $request->user()->id,
            'scheduled_at' => $validated['scheduled_at'],
            'type' => $validated['type'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ];
    }

    private function prescriptionData(Request $request, ?Prescription $prescription = null): array
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'medication_name' => ['required', 'string', 'max:255'],
            'dosage' => ['required', 'string', 'max:255'],
            'frequency' => ['required', 'string', 'max:255'],
            'duration' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        return [
            'reference' => $prescription?->reference ?? ('RX'.str_pad((string) (Prescription::count() + 1), 3, '0', STR_PAD_LEFT)),
            'patient_id' => (int) $validated['patient_id'],
            'doctor_id' => $request->user()->id,
            'medication_name' => $validated['medication_name'],
            'dosage' => $validated['dosage'],
            'frequency' => $validated['frequency'],
            'duration' => $validated['duration'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];
    }

    private function medicalRecordData(Request $request, ?MedicalRecord $medicalRecord = null): array
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'visit_date' => ['required', 'date'],
            'visit_reference' => ['required', 'string', 'max:255', Rule::unique('medical_records', 'visit_reference')->ignore($medicalRecord?->id)],
            'chief_complaint' => ['nullable', 'string'],
            'diagnosis' => ['nullable', 'string'],
            'treatment' => ['nullable', 'string'],
            'clinical_notes' => ['nullable', 'string'],
            'blood_pressure' => ['nullable', 'string', 'max:20'],
            'temperature' => ['nullable', 'numeric'],
            'pulse' => ['nullable', 'integer'],
            'weight' => ['nullable', 'integer'],
        ]);

        return [
            'patient_id' => (int) $validated['patient_id'],
            'doctor_id' => $request->user()->id,
            'visit_date' => $validated['visit_date'],
            'visit_reference' => $validated['visit_reference'],
            'chief_complaint' => $validated['chief_complaint'] ?? null,
            'diagnosis' => $validated['diagnosis'] ?? null,
            'treatment' => $validated['treatment'] ?? null,
            'clinical_notes' => $validated['clinical_notes'] ?? null,
            'blood_pressure' => $validated['blood_pressure'] ?? null,
            'temperature' => $validated['temperature'] ?? null,
            'pulse' => $validated['pulse'] ?? null,
            'weight' => $validated['weight'] ?? null,
        ];
    }

    private function userData(Request $request, ?User $user = null): array
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_initial' => ['nullable', 'string', 'max:5'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'role' => ['required', Rule::in(['admin', 'doctor', 'receptionist'])],
            'is_active' => ['required', 'boolean'],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
        ]);

        $data = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'middle_initial' => $validated['middle_initial'] ?? null,
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => (bool) $validated['is_active'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        return $data;
    }

    private function redirectBack(Request $request): RedirectResponse
    {
        return back()->with('status', 'saved');
    }
}
