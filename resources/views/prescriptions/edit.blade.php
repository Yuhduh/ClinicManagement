<x-app-layout>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4" x-data="{ open: true }">
        <div class="relative w-full max-w-2xl rounded-[28px] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.10)] max-h-[90vh] overflow-y-auto">
            <!-- Close Button -->
            <button onclick="window.location.href='{{ route('prescriptions.index', ['prescription' => $prescription->id]) }}'" class="absolute top-6 right-6 z-10 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Form -->
            <form method="POST" action="{{ route('prescriptions.update', $prescription) }}" class="p-8">
                @csrf
                @method('PATCH')
                
                <h2 class="text-2xl font-semibold text-slate-900 mb-6">Edit Prescription</h2>

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
                                <option value="{{ $patient->id }}" @selected(old('patient_id', $prescription->patient_id) == $patient->id)>
                                    {{ $patient->patient_code }} - {{ $patient->first_name }} {{ $patient->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Medications Section -->
                    <div class="border-t border-[#e5e9f1] pt-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-semibold text-slate-900">Medications</h3>
                        </div>

                        <!-- Medication 1 -->
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-slate-900 mb-2">Medication Name</label>
                                <input type="text" name="medication_name" value="{{ old('medication_name', $prescription->medication_name) }}" placeholder="e.g., Amoxicillin" class="w-full rounded-xl border @error('medication_name') border-red-300 @else border-[#e5e9f1] @enderror bg-[#f6f7fb] px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
                                @error('medication_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-slate-900 mb-2">Dosage</label>
                                    <input type="text" name="dosage" value="{{ old('dosage', $prescription->dosage) }}" placeholder="e.g., 500mg" class="w-full rounded-xl border @error('dosage') border-red-300 @else border-[#e5e9f1] @enderror bg-[#f6f7fb] px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
                                    @error('dosage')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-900 mb-2">Frequency</label>
                                    <input type="text" name="frequency" value="{{ old('frequency', $prescription->frequency) }}" placeholder="e.g., Twice daily" class="w-full rounded-xl border @error('frequency') border-red-300 @else border-[#e5e9f1] @enderror bg-[#f6f7fb] px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
                                    @error('frequency')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-900 mb-2">Duration</label>
                                    <input type="text" name="duration" value="{{ old('duration', $prescription->duration) }}" placeholder="e.g., 10 days" class="w-full rounded-xl border @error('duration') border-red-300 @else border-[#e5e9f1] @enderror bg-[#f6f7fb] px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
                                    @error('duration')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div>
                        <label class="block text-sm font-medium text-slate-900 mb-2">Additional Notes (Optional)</label>
                        <textarea name="notes" rows="3" placeholder="Instructions, warnings, or additional information..." class="w-full rounded-xl border @error('notes') border-red-300 @else border-[#e5e9f1] @enderror bg-[#f6f7fb] px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]">{{ old('notes', $prescription->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-8">
                    <button type="submit" class="flex-1 rounded-xl bg-[#2463eb] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#1f54c9] transition-colors">
                        Update Prescription
                    </button>
                    <a href="{{ route('prescriptions.index', ['prescription' => $prescription->id]) }}" class="flex-1 rounded-xl border border-[#e5e9f1] bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-600 hover:bg-[#f8fbff] transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
