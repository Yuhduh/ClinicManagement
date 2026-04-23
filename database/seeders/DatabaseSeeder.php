<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@clinic.com'],
            [
            'name' => 'Admin User',
            'role' => 'admin',
            'is_active' => true,
            ]
        );

        $doctorSarah = User::updateOrCreate(
            ['email' => 'doctor@clinic.com'],
            [
            'name' => 'Dr. Sarah Chen',
            'role' => 'doctor',
            'is_active' => true,
            ]
        );

        $doctorRobert = User::updateOrCreate(
            ['email' => 'doctor2@clinic.com'],
            [
            'name' => 'Dr. Robert Martinez',
            'role' => 'doctor',
            'is_active' => true,
            ]
        );

        $reception = User::updateOrCreate(
            ['email' => 'reception@clinic.com'],
            [
            'name' => 'Reception User',
            'role' => 'receptionist',
            'is_active' => true,
            ]
        );

        $receptionTwo = User::updateOrCreate(
            ['email' => 'reception2@clinic.com'],
            [
            'name' => 'Jennifer Lee',
            'role' => 'receptionist',
            'is_active' => true,
            ]
        );

        $patients = collect([
            ['patient_code' => 'P001', 'first_name' => 'John', 'last_name' => 'Smith', 'phone' => '+63 917 111 1001', 'gender' => 'male'],
            ['patient_code' => 'P002', 'first_name' => 'Sarah', 'last_name' => 'Johnson', 'phone' => '+63 917 111 1002', 'gender' => 'female'],
            ['patient_code' => 'P003', 'first_name' => 'Michael', 'last_name' => 'Williams', 'phone' => '+63 917 111 1003', 'gender' => 'male'],
            ['patient_code' => 'P004', 'first_name' => 'Emily', 'last_name' => 'Brown', 'phone' => '+63 917 111 1004', 'gender' => 'female'],
        ])->map(fn (array $patient) => Patient::updateOrCreate(['patient_code' => $patient['patient_code']], $patient));

        $appointments = [
            [$patients[0], $doctorSarah, Carbon::now()->setTime(9, 0), 'General Consultation', 'confirmed'],
            [$patients[1], $doctorSarah, Carbon::now()->setTime(10, 30), 'Annual Check-up', 'confirmed'],
            [$patients[2], $doctorRobert, Carbon::now()->setTime(14, 0), 'Specialist Consultation', 'pending'],
            [$patients[3], $doctorSarah, Carbon::tomorrow()->setTime(9, 30), 'Follow-up', 'confirmed'],
            [$patients[0], $doctorRobert, Carbon::tomorrow()->setTime(11, 0), 'Lab Results Review', 'cancelled'],
        ];

        foreach ($appointments as [$patient, $doctor, $scheduledAt, $type, $status]) {
            Appointment::updateOrCreate([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'scheduled_at' => $scheduledAt,
                'type' => $type,
            ], [
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'created_by' => $reception->id,
                'scheduled_at' => $scheduledAt,
                'type' => $type,
                'status' => $status,
            ]);
        }

        $prescriptions = [
            [$patients[2], $doctorRobert, 'RX003', 'Metoprolol', '50mg', 'Twice daily', '30 days', 'Take at same time each day.'],
            [$patients[0], $doctorSarah, 'RX001', 'Lisinopril', '10mg', 'Once daily', '30 days', 'Monitor blood pressure every morning.'],
            [$patients[1], $doctorSarah, 'RX002', 'Amoxicillin', '500mg', 'Three times daily', '7 days', 'Finish full antibiotic course.'],
        ];

        foreach ($prescriptions as [$patient, $doctor, $reference, $medication, $dosage, $frequency, $duration, $notes]) {
            Prescription::updateOrCreate([
                'reference' => $reference,
            ], [
                'reference' => $reference,
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'medication_name' => $medication,
                'dosage' => $dosage,
                'frequency' => $frequency,
                'duration' => $duration,
                'notes' => $notes,
            ]);
        }

        $records = [
            [$patients[0], $doctorSarah, 'V001', Carbon::parse('2026-03-25'), 'Elevated blood pressure', 'Hypertension (Stage 1)', 'Prescribed Lisinopril 10mg and Aspirin 81mg', 'Patient reports feeling dizzy. Advised lifestyle modifications.', '145/92', 98.6, 78, 185],
            [$patients[0], $doctorRobert, 'V003', Carbon::parse('2026-02-15'), 'Routine check-up', 'Pre-hypertension', 'Lifestyle counseling', 'Recommended dietary changes and stress management.', '135/88', 98.4, 72, 188],
            [$patients[2], $doctorRobert, 'V005', Carbon::parse('2026-03-28'), 'Chest discomfort', 'Mild arrhythmia', 'ECG monitoring and medication adjustment', 'No acute distress observed.', '130/84', 98.7, 82, 176],
        ];

        foreach ($records as [$patient, $doctor, $ref, $visitDate, $complaint, $diagnosis, $treatment, $notes, $bp, $temp, $pulse, $weight]) {
            MedicalRecord::updateOrCreate([
                'visit_reference' => $ref,
            ], [
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'visit_date' => $visitDate,
                'visit_reference' => $ref,
                'chief_complaint' => $complaint,
                'diagnosis' => $diagnosis,
                'treatment' => $treatment,
                'clinical_notes' => $notes,
                'blood_pressure' => $bp,
                'temperature' => $temp,
                'pulse' => $pulse,
                'weight' => $weight,
            ]);
        }
    }
}
