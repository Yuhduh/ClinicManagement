@php
    $title = match ($active) {
        'appointments' => 'Manage Appointments',
        'patients' => 'Patient Directory',
        'records' => 'Medical Records',
        'prescriptions' => 'Prescription Requests',
        'settings' => 'System Settings',
        default => 'Reception Dashboard',
    };

    $metrics = $metrics ?? [];
    $appointments = $appointments ?? collect();
    $patients = $patients ?? collect();
    $doctors = $doctors ?? collect();
    $editingAppointment = $editingAppointment ?? null;
    $editingPatient = $editingPatient ?? null;
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-4xl font-bold tracking-tight text-slate-900">{{ $title }}</h1>
            <p class="text-slate-600">Front-desk operations with fast data entry and minimal clicks.</p>
        </div>
        <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Register New Patient</button>
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

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex flex-wrap gap-2">
            <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Create Appointment</button>
            <button class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">View</button>
            <button class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Update</button>
            <button class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 hover:bg-rose-100">Delete</button>
        </div>

        <div class="mb-4 inline-flex rounded-lg bg-slate-100 p-1 text-sm">
            <button class="rounded-md bg-white px-3 py-1.5 text-slate-900 shadow">List View</button>
            <button class="rounded-md px-3 py-1.5 text-slate-600">Calendar View</button>
        </div>

        <form method="POST" action="{{ $editingAppointment ? route('receptionist.appointments.update', $editingAppointment) : route('receptionist.appointments.store') }}" class="space-y-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
            @csrf
            @if ($editingAppointment)
                @method('PUT')
            @endif

            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                <select name="patient_id" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400">
                    <option value="">Select patient</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}" @selected(old('patient_id', $editingAppointment?->patient_id) == $patient->id)>{{ $patient->patient_code }} - {{ $patient->full_name }}</option>
                    @endforeach
                </select>
                <select name="doctor_id" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400">
                    <option value="">Select doctor</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @selected(old('doctor_id', $editingAppointment?->doctor_id) == $doctor->id)>{{ $doctor->name }}</option>
                    @endforeach
                </select>
                <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', $editingAppointment?->scheduled_at?->format('Y-m-d\TH:i')) }}" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="text" name="type" value="{{ old('type', $editingAppointment?->type) }}" placeholder="Visit type" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <select name="status" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400">
                    @foreach (['confirmed', 'pending', 'cancelled'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $editingAppointment?->status ?? 'pending') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <textarea name="notes" rows="3" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" placeholder="Appointment notes">{{ old('notes', $editingAppointment?->notes) }}</textarea>

            <div class="flex items-center gap-2">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">{{ $editingAppointment ? 'Update Appointment' : 'Save Appointment' }}</button>
                @if ($editingAppointment)
                    <a href="{{ route('receptionist.appointments') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-slate-600">
                    <tr>
                        <th class="px-4 py-3 font-medium">Patient</th>
                        <th class="px-4 py-3 font-medium">Date & Time</th>
                        <th class="px-4 py-3 font-medium">Doctor</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($appointments as $appointment)
                        <tr>
                            <td class="px-4 py-3 text-slate-900">{{ $appointment->patient->full_name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $appointment->scheduled_at->format('M d, Y · h:i A') }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $appointment->doctor->name }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="rounded-full px-2 py-1 text-xs font-medium {{ $appointment->status === 'confirmed' ? 'bg-emerald-100 text-emerald-700' : ($appointment->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">{{ ucfirst($appointment->status) }}</span>
                                    <div class="flex items-center gap-2 text-sm">
                                        <a href="{{ route('receptionist.appointments', ['edit_appointment' => $appointment->id]) }}" class="text-blue-600 hover:text-blue-700">Edit</a>
                                        <form method="POST" action="{{ route('receptionist.appointments.destroy', $appointment) }}" onsubmit="return confirm('Delete this appointment?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-700">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-500">No appointments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Manage Patients (CRUD)</h2>
        <form method="POST" action="{{ $editingPatient ? route('receptionist.patients.update', $editingPatient) : route('receptionist.patients.store') }}" class="mt-4 space-y-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
            @csrf
            @if ($editingPatient)
                @method('PUT')
            @endif

            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                <input type="text" name="patient_code" value="{{ old('patient_code', $editingPatient?->patient_code) }}" placeholder="Patient code" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="text" name="first_name" value="{{ old('first_name', $editingPatient?->first_name) }}" placeholder="First name" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="text" name="last_name" value="{{ old('last_name', $editingPatient?->last_name) }}" placeholder="Last name" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="email" name="email" value="{{ old('email', $editingPatient?->email) }}" placeholder="Email" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="text" name="phone" value="{{ old('phone', $editingPatient?->phone) }}" placeholder="Phone" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $editingPatient?->date_of_birth?->format('Y-m-d')) }}" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <select name="gender" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400">
                    <option value="">Select gender</option>
                    @foreach (['male', 'female', 'other'] as $gender)
                        <option value="{{ $gender }}" @selected(old('gender', $editingPatient?->gender) === $gender)>{{ ucfirst($gender) }}</option>
                    @endforeach
                </select>
            </div>

            <textarea name="address" rows="3" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" placeholder="Address">{{ old('address', $editingPatient?->address) }}</textarea>

            <div class="flex items-center gap-2">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">{{ $editingPatient ? 'Update Patient' : 'Save Patient' }}</button>
                @if ($editingPatient)
                    <a href="{{ route('receptionist.patients') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                @endif
            </div>
        </form>

        <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-slate-600">
                    <tr>
                        <th class="px-4 py-3 font-medium">ID</th>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Phone</th>
                        <th class="px-4 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($patients as $patient)
                        <tr>
                            <td class="px-4 py-3 text-slate-600">{{ $patient->patient_code }}</td>
                            <td class="px-4 py-3 text-slate-900">{{ $patient->full_name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $patient->phone ?: 'N/A' }}</td>
                            <td class="px-4 py-3 text-slate-600">
                                <div class="flex items-center gap-2 text-sm">
                                    <a href="{{ route('receptionist.patients', ['edit_patient' => $patient->id]) }}" class="text-blue-600 hover:text-blue-700">Edit</a>
                                    <form method="POST" action="{{ route('receptionist.patients.destroy', $patient) }}" onsubmit="return confirm('Delete this patient?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-700">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-500">No patients registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
