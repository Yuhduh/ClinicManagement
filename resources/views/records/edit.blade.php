<x-app-layout>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
        <div class="relative w-full max-w-2xl rounded-[28px] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.10)] max-h-[90vh] overflow-y-auto">
            <!-- Close Button -->
            <button onclick="window.location.href='{{ route('records.index', ['patient' => $record->patient_id]) }}'" class="absolute top-6 right-6 z-10 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Form -->
            <form method="POST" action="{{ route('records.update', $record) }}" class="p-8">
                @csrf
                @method('PATCH')
                
                <h2 class="text-2xl font-semibold text-slate-900 mb-1">Edit Patient Visit</h2>
                <p class="text-sm text-slate-500 mb-6">Update clinical notes for {{ $record->patient->first_name }}</p>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                        <p class="text-sm font-medium text-red-900 mb-2">Please fix the following errors:</p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-4">
                    <!-- Patient -->
                    <div>
                        <label class="block text-sm font-medium text-slate-900 mb-2">Patient</label>
                        <select name="patient_id" class="w-full rounded-xl border @error('patient_id') border-red-300 @else border-[#e5e9f1] @enderror bg-[#f6f7fb] px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]">
                            <option value="">Select patient</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}" @selected(old('patient_id', $record->patient_id) == $patient->id)>
                                    {{ $patient->patient_code }} - {{ $patient->first_name }} {{ $patient->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Visit Date -->
                    <div>
                        <label class="block text-sm font-medium text-slate-900 mb-2">Visit Date</label>
                        <input type="date" name="visit_date" value="{{ old('visit_date', $record->visit_date?->format('Y-m-d')) }}" class="w-full rounded-xl border @error('visit_date') border-red-300 @else border-[#e5e9f1] @enderror bg-[#f6f7fb] px-4 py-2.5 text-slate-900 focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
                        @error('visit_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Chief Complaint -->
                    <div>
                        <label class="block text-sm font-medium text-slate-900 mb-2">Chief Complaint</label>
                        <input type="text" name="chief_complaint" value="{{ old('chief_complaint', $record->chief_complaint) }}" placeholder="Primary reason for visit" class="w-full rounded-xl border @error('chief_complaint') border-red-300 @else border-[#e5e9f1] @enderror bg-[#f6f7fb] px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
                        @error('chief_complaint')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vital Signs -->
                    <div class="border-t border-[#e5e9f1] pt-4">
                        <label class="block text-sm font-medium text-slate-900 mb-3">Vital Signs</label>
                        <div class="grid grid-cols-4 gap-3">
                            <div>
                                <label class="block text-xs text-slate-500 font-medium mb-1">Blood Pressure</label>
                                <input type="text" name="blood_pressure" value="{{ old('blood_pressure', $record->blood_pressure) }}" placeholder="120/80" class="w-full rounded-xl border @error('blood_pressure') border-red-300 @else border-[#e5e9f1] @enderror bg-[#f6f7fb] px-3 py-2 text-sm text-slate-900 focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
                                @error('blood_pressure')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 font-medium mb-1">Temperature</label>
                                <input type="text" name="temperature" value="{{ old('temperature', $record->temperature) }}" placeholder="98.6°F" class="w-full rounded-xl border @error('temperature') border-red-300 @else border-[#e5e9f1] @enderror bg-[#f6f7fb] px-3 py-2 text-sm text-slate-900 focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
                                @error('temperature')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 font-medium mb-1">Pulse</label>
                                <input type="text" name="pulse" value="{{ old('pulse', $record->pulse) }}" placeholder="72 bpm" class="w-full rounded-xl border @error('pulse') border-red-300 @else border-[#e5e9f1] @enderror bg-[#f6f7fb] px-3 py-2 text-sm text-slate-900 focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
                                @error('pulse')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 font-medium mb-1">Weight</label>
                                <input type="text" name="weight" value="{{ old('weight', $record->weight) }}" placeholder="180 lbs" class="w-full rounded-xl border @error('weight') border-red-300 @else border-[#e5e9f1] @enderror bg-[#f6f7fb] px-3 py-2 text-sm text-slate-900 focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
                                @error('weight')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Diagnosis -->
                    <div>
                        <label class="block text-sm font-medium text-slate-900 mb-2">Diagnosis</label>
                        <input type="text" name="diagnosis" value="{{ old('diagnosis', $record->diagnosis) }}" placeholder="Clinical diagnosis" class="w-full rounded-xl border @error('diagnosis') border-red-300 @else border-[#e5e9f1] @enderror bg-[#f6f7fb] px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
                        @error('diagnosis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Treatment Plan -->
                    <div>
                        <label class="block text-sm font-medium text-slate-900 mb-2">Treatment Plan</label>
                        <textarea name="treatment" rows="2" placeholder="Prescribed medications, procedures, or recommendations" class="w-full rounded-xl border @error('treatment') border-red-300 @else border-[#e5e9f1] @enderror bg-[#f6f7fb] px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]">{{ old('treatment', $record->treatment) }}</textarea>
                        @error('treatment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Clinical Notes -->
                    <div>
                        <label class="block text-sm font-medium text-slate-900 mb-2">Clinical Notes</label>
                        <textarea name="clinical_notes" rows="3" placeholder="Detailed clinical observations and notes" class="w-full rounded-xl border @error('clinical_notes') border-red-300 @else border-[#e5e9f1] @enderror bg-[#f6f7fb] px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]">{{ old('clinical_notes', $record->clinical_notes) }}</textarea>
                        @error('clinical_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-8">
                    <button type="submit" class="flex-1 rounded-xl bg-[#2463eb] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#1f54c9] transition-colors">
                        Update Visit
                    </button>
                    <a href="{{ route('records.index', ['patient' => $record->patient_id]) }}" class="flex-1 rounded-xl border border-[#e5e9f1] bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-600 hover:bg-[#f8fbff] transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
