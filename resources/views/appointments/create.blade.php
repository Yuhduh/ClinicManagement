<x-app-layout>
    <section class="px-4 py-6">
        <div class="mx-auto w-full max-w-2xl rounded-[28px] border border-[#d8e0eb] bg-white p-6 shadow-[0_20px_60px_rgba(15,23,42,0.10)] md:p-8">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-[#172033] md:text-[28px]">Create New Appointment</h1>
                    <p class="mt-1 text-sm text-[#6b7280] md:text-base">Fill in the details to schedule a new appointment.</p>
                </div>
                <a href="{{ route('appointments.index') }}" class="flex h-9 w-9 items-center justify-center rounded-full text-[#6b7280] transition hover:bg-[#f3f6fb] hover:text-[#172033]" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>

            <form method="POST" action="{{ route('appointments.store') }}" class="space-y-6">
                @csrf

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">Patient</label>
                        <select name="patient_id" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#6b7280] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]">
                            <option value="">Select patient</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id)>{{ $patient->patient_code }} - {{ $patient->first_name }} {{ $patient->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">Doctor</label>
                        <select name="doctor_id" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#6b7280] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]">
                            <option value="">Select doctor</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}" @selected(old('doctor_id') == $doctor->id)>{{ $doctor->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">Date</label>
                        <input type="date" name="appointment_date" value="{{ old('appointment_date') }}" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">Time</label>
                        <input type="time" name="appointment_time" value="{{ old('appointment_time') }}" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">Appointment Type</label>
                        <input type="text" name="type" value="{{ old('type') }}" placeholder="e.g. General Consultation" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] placeholder:text-[#8a94a6] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">Status</label>
                        <select name="status" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]">
                            @foreach (['pending', 'confirmed', 'cancelled'] as $status)
                                <option value="{{ $status }}" @selected(old('status', 'pending') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">Notes (Optional)</label>
                        <textarea name="notes" rows="4" placeholder="Any additional notes..." class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] placeholder:text-[#8a94a6] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                    <a href="{{ route('appointments.index') }}" class="rounded-xl border border-[#d7deea] bg-white px-5 py-3 text-sm font-medium text-[#4a5f7d] hover:bg-[#f8fbff]">Cancel</a>
                    <button type="submit" class="rounded-xl bg-[#2463eb] px-5 py-3 text-sm font-medium text-white hover:bg-[#1f54c9]">Create Appointment</button>
                </div>
            </form>
        </div>
    </section>
</x-app-layout>
