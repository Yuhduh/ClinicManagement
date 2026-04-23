<x-app-layout>
    <section class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Medical Records</h1>
                <p class="text-xs text-slate-500">Access comprehensive patient medical history</p>
            </div>
            <a href="{{ route('records.create') }}" class="rounded-lg bg-[#2463eb] px-4 py-2 text-sm font-medium text-white hover:bg-[#1f54c9]">+ Document Visit</a>
        </div>

        <div class="grid gap-4 xl:grid-cols-[0.65fr_1.35fr]">
            <!-- Patient Selection (Left) -->
            <div class="rounded-2xl border border-[#e5e9f1] bg-white shadow-sm p-4">
                <h3 class="text-sm font-semibold text-slate-900 mb-3">Select Patient</h3>
                <div class="space-y-2">
                    @forelse ($patients as $patient)
                        <a href="{{ route('records.index', ['patient' => $patient->id]) }}" class="block rounded-xl border transition-all {{ $selectedPatientId === $patient->id ? 'border-[#2463eb] bg-[#eef4ff] shadow-md' : 'border-[#e5e9f1] bg-white hover:border-[#d8e0eb]' }} p-3">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-[#d8e0eb] flex items-center justify-center">
                                    <svg class="w-4 h-4 text-[#2463eb]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $patient->first_name }} {{ $patient->last_name }}</p>
                                    <p class="text-xs text-slate-500 truncate">ID: {{ $patient->patient_code }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-sm text-slate-500">No patients found</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Patient Details (Right) -->
            @if ($selectedPatient)
                <div class="rounded-2xl border border-[#e5e9f1] bg-white shadow-sm p-6 space-y-6">
                    <!-- Patient Header -->
                    <div class="flex items-start gap-4">
                        <div class="w-20 h-20 rounded-full bg-[#d8e0eb] flex items-center justify-center flex-shrink-0">
                            <svg class="w-10 h-10 text-[#2463eb]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-semibold text-slate-900">{{ $selectedPatient->first_name }} {{ $selectedPatient->last_name }}</h2>
                            <div class="grid grid-cols-4 gap-4 mt-4">
                                <div>
                                    <p class="text-xs text-slate-500 font-medium">Age</p>
                                    <p class="text-sm font-semibold text-slate-900">{{ $selectedPatient->date_of_birth?->age ?? '—' }} years</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-medium">Gender</p>
                                    <p class="text-sm font-semibold text-slate-900">{{ ucfirst($selectedPatient->gender ?? '—') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-medium">Blood Type</p>
                                    <p class="text-sm font-semibold text-slate-900">{{ $selectedPatient->blood_type ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-medium">Patient ID</p>
                                    <p class="text-sm font-semibold text-slate-900">{{ $selectedPatient->patient_code }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Allergies -->
                    @if ($selectedPatient->allergies)
                        <div class="border-t border-[#e5e9f1] pt-4">
                            <p class="text-xs text-slate-500 font-medium mb-2">Allergies</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach (explode(',', $selectedPatient->allergies) as $allergy)
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700">{{ trim($allergy) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Tabs -->
                    <div class="border-t border-[#e5e9f1] pt-4">
                        <div class="flex gap-6 border-b border-[#e5e9f1]">
                            <a href="{{ route('records.index', ['patient' => $selectedPatient->id, 'tab' => 'visits']) }}" class="pb-3 text-sm font-medium {{ $activeTab === 'visits' ? 'border-b-2 border-[#2463eb] text-[#2463eb]' : 'text-slate-600 hover:text-slate-900' }}">
                                📋 Visit History
                            </a>
                            <a href="{{ route('records.index', ['patient' => $selectedPatient->id, 'tab' => 'prescriptions']) }}" class="pb-3 text-sm font-medium {{ $activeTab === 'prescriptions' ? 'border-b-2 border-[#2463eb] text-[#2463eb]' : 'text-slate-600 hover:text-slate-900' }}">
                                💊 Prescriptions
                            </a>
                        </div>

                        <!-- Visit History Tab -->
                        @if ($activeTab === 'visits')
                            <div class="pt-4 space-y-4">
                                @forelse ($visits as $visit)
                                    <div class="border border-[#e5e9f1] rounded-xl p-4 space-y-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $visit->visit_date->format('F j, Y') }}
                                            </div>
                                            <p class="text-xs text-slate-500">Visit ID: {{ $visit->visit_reference ?? 'V001' }}</p>
                                        </div>

                                        <!-- Vital Signs -->
                                        <div class="grid grid-cols-4 gap-3">
                                            <div class="rounded-lg bg-[#fbfdff] border border-[#e5e9f1] p-3">
                                                <div class="flex items-center gap-1 text-red-600 text-xs font-medium mb-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path>
                                                    </svg>
                                                    BP
                                                </div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $visit->blood_pressure ?? '—' }}</p>
                                            </div>
                                            <div class="rounded-lg bg-[#fbfdff] border border-[#e5e9f1] p-3">
                                                <div class="flex items-center gap-1 text-orange-600 text-xs font-medium mb-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 2.476c.44 2.449 2.139 5.018 5.222 7.765 3.075-2.718 4.778-5.3 5.145-7.715z"></path>
                                                    </svg>
                                                    Temp
                                                </div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $visit->temperature ?? '—' }}</p>
                                            </div>
                                            <div class="rounded-lg bg-[#fbfdff] border border-[#e5e9f1] p-3">
                                                <div class="flex items-center gap-1 text-blue-600 text-xs font-medium mb-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                                                    </svg>
                                                    Pulse
                                                </div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $visit->pulse ?? '—' }}</p>
                                            </div>
                                            <div class="rounded-lg bg-[#fbfdff] border border-[#e5e9f1] p-3">
                                                <div class="flex items-center gap-1 text-green-600 text-xs font-medium mb-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                                                    </svg>
                                                    Weight
                                                </div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $visit->weight ?? '—' }}</p>
                                            </div>
                                        </div>

                                        <!-- Clinical Details -->
                                        @if ($visit->chief_complaint)
                                            <div>
                                                <p class="text-xs text-slate-500 font-medium">Chief Complaint</p>
                                                <p class="text-sm text-slate-600">{{ $visit->chief_complaint }}</p>
                                            </div>
                                        @endif

                                        @if ($visit->diagnosis)
                                            <div>
                                                <p class="text-xs text-slate-500 font-medium">Diagnosis</p>
                                                <p class="text-sm text-slate-600">{{ $visit->diagnosis }}</p>
                                            </div>
                                        @endif

                                        @if ($visit->treatment)
                                            <div>
                                                <p class="text-xs text-slate-500 font-medium">Treatment</p>
                                                <p class="text-sm text-slate-600">{{ $visit->treatment }}</p>
                                            </div>
                                        @endif

                                        @if ($visit->clinical_notes)
                                            <div>
                                                <p class="text-xs text-slate-500 font-medium">Clinical Notes</p>
                                                <p class="text-sm text-slate-600">{{ $visit->clinical_notes }}</p>
                                            </div>
                                        @endif

                                        <div class="pt-2 flex gap-2">
                                            <a href="{{ route('records.edit', $visit) }}" class="flex-1 rounded-lg bg-[#2463eb] px-3 py-2 text-center text-xs font-medium text-white hover:bg-[#1f54c9]">Edit</a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <p class="text-slate-500">No visit records found</p>
                                    </div>
                                @endforelse
                            </div>
                        @endif

                        <!-- Prescriptions Tab -->
                        @if ($activeTab === 'prescriptions')
                            <div class="pt-4 space-y-3">
                                @forelse ($prescriptions as $prescription)
                                    <div class="border border-[#e5e9f1] rounded-xl p-3 space-y-2">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $prescription->medication_name }}</p>
                                                <p class="text-xs text-slate-500">{{ $prescription->reference }}</p>
                                            </div>
                                            <p class="text-xs text-slate-500">{{ $prescription->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <div class="grid grid-cols-3 gap-2 text-xs">
                                            <div>
                                                <p class="text-slate-500">Dosage</p>
                                                <p class="font-medium text-slate-900">{{ $prescription->dosage }}</p>
                                            </div>
                                            <div>
                                                <p class="text-slate-500">Frequency</p>
                                                <p class="font-medium text-slate-900">{{ $prescription->frequency }}</p>
                                            </div>
                                            <div>
                                                <p class="text-slate-500">Duration</p>
                                                <p class="font-medium text-slate-900">{{ $prescription->duration ?? '—' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <p class="text-slate-500">No prescriptions found</p>
                                    </div>
                                @endforelse
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Success Modal -->
    @if (session('visitSuccess'))
        <div class="fixed inset-0 z-50 flex items-center justify-center" x-data="{ open: true }" x-show="open" x-transition>
            <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
            <div class="relative rounded-2xl bg-white p-6 shadow-lg max-w-sm mx-4">
                <button @click="open = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Visit Documented</h3>
                </div>
                <p class="text-sm text-slate-600 mb-6">The patient visit has been successfully recorded.</p>
                <button @click="open = false" class="w-full rounded-lg bg-[#2463eb] px-4 py-2 text-sm font-medium text-white hover:bg-[#1f54c9]">
                    Done
                </button>
            </div>
        </div>
    @endif
</x-app-layout>
