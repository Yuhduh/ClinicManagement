@php
    $title = match ($active) {
        'appointments' => 'Manage Appointments',
        'patients' => 'Patient Directory',
        'prescriptions' => 'Prescription Management',
        'records' => 'Clinical History',
        'settings' => 'System Settings',
        default => 'Doctor Dashboard',
    };

    $metrics = $metrics ?? [];
    $appointments = $appointments ?? collect();
    $patients = $patients ?? collect();
    $prescriptions = $prescriptions ?? collect();
    $records = $records ?? collect();
    $editingPrescription = $editingPrescription ?? null;
    $editingRecord = $editingRecord ?? null;
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-4xl font-bold tracking-tight text-slate-900">{{ $title }}</h1>
            <p class="text-slate-600">Clinical operations and patient care workflow.</p>
        </div>
        <div class="rounded-lg bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700">Care-focused overview</div>
    </div>

    <div class="grid gap-4 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Today's Appointments</p>
            <p class="mt-2 text-4xl font-bold text-slate-900">{{ $metrics['today_appointments'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Confirmed</p>
            <p class="mt-2 text-4xl font-bold text-slate-900">{{ $metrics['confirmed'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Pending</p>
            <p class="mt-2 text-4xl font-bold text-slate-900">{{ $metrics['pending'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Patients</p>
            <p class="mt-2 text-4xl font-bold text-slate-900">{{ $metrics['total_patients'] ?? 0 }}</p>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-slate-900">Appointments</h2>
                <div class="inline-flex rounded-lg bg-slate-100 p-1 text-sm">
                    <button class="rounded-md bg-white px-3 py-1.5 text-slate-900 shadow">Today's List</button>
                </div>
            </div>

            <div class="space-y-3">
                @forelse ($appointments as $appointment)
                    <div class="rounded-xl border border-slate-200 p-3">
                        <div class="flex items-center justify-between gap-2">
                            <p class="font-medium text-slate-900">{{ $appointment->patient->full_name }}</p>
                            <span class="rounded-full px-2 py-1 text-xs font-medium {{ $appointment->status === 'confirmed' ? 'bg-emerald-100 text-emerald-700' : ($appointment->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </div>
                        <div class="mt-2 flex items-center justify-between gap-3">
                            <p class="text-sm text-slate-500">{{ $appointment->scheduled_at->format('h:i A') }} · {{ $appointment->type }}</p>
                            <p class="text-sm text-slate-500">Dr. {{ $appointment->doctor->display_name }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No appointments scheduled.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Patient Consultation Suite</h2>
            <p class="mt-1 text-sm text-slate-500">Document visit details and treatment notes quickly.</p>
            <textarea rows="8" class="mt-4 w-full rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" placeholder="Clinical notes, findings, diagnosis, and treatment plan..."></textarea>
        </section>
    </div>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Prescription Management</h2>
        <form method="POST" action="{{ $editingPrescription ? route('doctor.prescriptions.update', $editingPrescription) : route('doctor.prescriptions.store') }}" class="mt-4 space-y-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
            @csrf
            @if ($editingPrescription)
                @method('PUT')
            @endif

            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                <select name="patient_id" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400">
                    <option value="">Select patient</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}" @selected(old('patient_id', $editingPrescription?->patient_id) == $patient->id)>{{ $patient->patient_code }} - {{ $patient->full_name }}</option>
                    @endforeach
                </select>
                <input type="text" name="medication_name" value="{{ old('medication_name', $editingPrescription?->medication_name) }}" placeholder="Medication name" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="text" name="dosage" value="{{ old('dosage', $editingPrescription?->dosage) }}" placeholder="Dosage" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="text" name="frequency" value="{{ old('frequency', $editingPrescription?->frequency) }}" placeholder="Frequency" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="text" name="duration" value="{{ old('duration', $editingPrescription?->duration) }}" placeholder="Duration" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
            </div>

            <textarea name="notes" rows="3" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" placeholder="Prescription notes">{{ old('notes', $editingPrescription?->notes) }}</textarea>

            <div class="flex items-center gap-2">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">{{ $editingPrescription ? 'Update Prescription' : 'Save Prescription' }}</button>
                @if ($editingPrescription)
                    <a href="{{ route('doctor.prescriptions') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                @endif
            </div>
        </form>

        <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-slate-600">
                    <tr>
                        <th class="px-4 py-3 font-medium">Patient</th>
                        <th class="px-4 py-3 font-medium">Medication</th>
                        <th class="px-4 py-3 font-medium">Dosage</th>
                        <th class="px-4 py-3 font-medium">Frequency</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($prescriptions as $prescription)
                        <tr>
                            <td class="px-4 py-3 text-slate-900">{{ $prescription->patient->full_name }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $prescription->medication_name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $prescription->dosage }}</td>
                            <td class="px-4 py-3 text-slate-600">
                                <div class="flex items-center justify-between gap-3">
                                    <span>{{ $prescription->frequency }}</span>
                                    <div class="flex items-center gap-2 text-sm">
                                        <a href="{{ route('doctor.prescriptions', ['edit_prescription' => $prescription->id]) }}" class="text-blue-600 hover:text-blue-700">Edit</a>
                                        <button 
                                            type="button"
                                            data-confirm-delete 
                                            data-confirm-modal="delete-doctor-prescription-{{ $prescription->id }}"
                                            data-confirm-message="Are you sure you want to delete this prescription? This action cannot be undone."
                                            class="text-rose-600 hover:text-rose-700"
                                        >
                                            Delete
                                        </button>
                                        <form id="delete-form-{{ $prescription->id }}" method="POST" action="{{ route('doctor.prescriptions.destroy', $prescription) }}" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <x-confirm-modal 
                                            :id="'delete-doctor-prescription-' . $prescription->id"
                                            title="Delete Prescription"
                                            message="Are you sure you want to delete this prescription? This action cannot be undone."
                                            confirmText="Delete"
                                            cancelText="Cancel"
                                        />
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-500">No prescriptions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Clinical History Timeline</h2>
        <form method="POST" action="{{ $editingRecord ? route('doctor.records.update', $editingRecord) : route('doctor.records.store') }}" class="mt-4 space-y-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
            @csrf
            @if ($editingRecord)
                @method('PUT')
            @endif

            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                <select name="patient_id" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400">
                    <option value="">Select patient</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}" @selected(old('patient_id', $editingRecord?->patient_id) == $patient->id)>{{ $patient->patient_code }} - {{ $patient->full_name }}</option>
                    @endforeach
                </select>
                <input type="date" name="visit_date" value="{{ old('visit_date', $editingRecord?->visit_date?->format('Y-m-d')) }}" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="text" name="visit_reference" value="{{ old('visit_reference', $editingRecord?->visit_reference) }}" placeholder="Visit reference" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
            </div>

            <div class="grid gap-3 md:grid-cols-2">
                <input type="text" name="chief_complaint" value="{{ old('chief_complaint', $editingRecord?->chief_complaint) }}" placeholder="Chief complaint" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="text" name="diagnosis" value="{{ old('diagnosis', $editingRecord?->diagnosis) }}" placeholder="Diagnosis" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="text" name="treatment" value="{{ old('treatment', $editingRecord?->treatment) }}" placeholder="Treatment" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="text" name="blood_pressure" value="{{ old('blood_pressure', $editingRecord?->blood_pressure) }}" placeholder="Blood pressure" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="number" step="0.1" name="temperature" value="{{ old('temperature', $editingRecord?->temperature) }}" placeholder="Temperature" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="number" name="pulse" value="{{ old('pulse', $editingRecord?->pulse) }}" placeholder="Pulse" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="number" name="weight" value="{{ old('weight', $editingRecord?->weight) }}" placeholder="Weight" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
            </div>

            <textarea name="clinical_notes" rows="4" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" placeholder="Clinical notes">{{ old('clinical_notes', $editingRecord?->clinical_notes) }}</textarea>

            <div class="flex items-center gap-2">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">{{ $editingRecord ? 'Update Record' : 'Save Record' }}</button>
                @if ($editingRecord)
                    <a href="{{ route('doctor.records') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                @endif
            </div>
        </form>

        <div class="mt-4 space-y-3">
            @forelse ($records as $record)
                <div class="rounded-xl border border-slate-200 p-3">
                    <div class="flex items-center justify-between gap-3">
                        <p class="font-medium text-slate-900">{{ $record->visit_date->format('F d, Y') }} · {{ $record->patient->full_name }}</p>
                        <div class="flex items-center gap-2 text-sm">
                            <a href="{{ route('doctor.records', ['edit_record' => $record->id]) }}" class="text-blue-600 hover:text-blue-700">Edit</a>
                            <button 
                                type="button"
                                data-confirm-delete 
                                data-confirm-modal="delete-record-{{ $record->id }}"
                                data-confirm-message="Are you sure you want to delete this medical record? This action cannot be undone."
                                class="text-rose-600 hover:text-rose-700"
                            >
                                Delete
                            </button>
                            <form id="delete-form-{{ $record->id }}" method="POST" action="{{ route('doctor.records.destroy', $record) }}" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <x-confirm-modal 
                                :id="'delete-record-' . $record->id"
                                title="Delete Record"
                                message="Are you sure you want to delete this medical record? This action cannot be undone."
                                confirmText="Delete"
                                cancelText="Cancel"
                            />
                        </div>
                    </div>
                    <p class="text-sm text-slate-600">{{ $record->diagnosis ?: 'No diagnosis entered yet.' }}</p>
                </div>
            @empty
                <p class="text-sm text-slate-500">No clinical history records available.</p>
            @endforelse
        </div>
    </section>
</div>
