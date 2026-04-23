<x-app-layout>
    @if (session('patientSuccess'))
        <div x-data="{ open: true }" x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/35 px-4 py-6">
            <div class="w-full max-w-md rounded-2xl border border-[#d8e0eb] bg-white p-6 shadow-[0_20px_60px_rgba(15,23,42,0.18)]">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-[#dff6ea] text-[#198754]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-[#172033]">Patient Registered</h2>
                            <p class="mt-1 text-sm text-[#6b7280]">{{ session('patientSuccess') }}</p>
                        </div>
                    </div>
                    <button type="button" @click="open = false" class="rounded-full p-1 text-[#8aa0bf] transition hover:bg-[#f3f6fb] hover:text-[#172033]" aria-label="Close confirmation">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-5 flex justify-end">
                    <button type="button" @click="open = false" class="rounded-lg bg-[#2463eb] px-4 py-2 text-sm font-medium text-white hover:bg-[#1f54c9]">Continue</button>
                </div>
            </div>
        </div>
    @endif

    <section class="space-y-5 rounded-2xl bg-[#edf2f9] p-5">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-[28px] font-semibold leading-tight text-[#172033]">Patients</h1>
                <p class="text-sm text-[#4a5f7d]">Manage patient records and information</p>
            </div>
            <a href="{{ route('patients.create') }}" class="rounded-lg bg-[#2463eb] px-4 py-2 text-sm font-medium text-white hover:bg-[#1f54c9]">Register New Patient</a>
        </div>

        <form method="GET" action="{{ route('patients.index') }}" class="rounded-2xl border border-[#d8e0eb] bg-white p-3.5">
            <label class="relative block">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-[#8aa0bf]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, ID, email, or phone..." class="w-full rounded-xl border border-[#d7deea] bg-[#f8fbff] py-2.5 pl-10 pr-3 text-sm text-[#334155] placeholder:text-[#94a3b8] focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
            </label>
        </form>

        <div class="grid gap-5 xl:grid-cols-[minmax(0,1.55fr)_minmax(320px,0.85fr)]">
            <section class="rounded-2xl border border-[#d8e0eb] bg-white p-4 shadow-[0_2px_8px_rgba(15,23,42,0.05)]">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-base font-medium text-[#172033]">Patient Directory</h2>
                    <span class="text-xs text-[#7184a0]">{{ $patients->total() }} records</span>
                </div>

                <div class="space-y-3">
                    @forelse ($patients as $patient)
                        <a href="{{ route('patients.index', array_filter(['search' => $search, 'patient' => $patient->id])) }}" class="flex items-center gap-3 rounded-xl border px-3 py-3 transition {{ optional($selectedPatient)->id === $patient->id ? 'border-[#8fb4ff] bg-[#eef4ff] shadow-[0_0_0_1px_rgba(36,99,235,0.08)]' : 'border-[#e5ebf3] bg-white hover:border-[#c9d7ee] hover:bg-[#fbfdff]' }}">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#e6efff] text-[#2463eb]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5.121 17.804A9 9 0 1118.88 17.8M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-[#172033]">{{ $patient->full_name }}</p>
                                <p class="text-xs text-[#4a5f7d]">ID: {{ $patient->patient_code }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="rounded-full border border-[#e1e8f2] px-2 py-0.5 text-[11px] capitalize text-[#6b7280]">{{ $patient->gender ?: 'unknown' }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#8aa0bf]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-xl border border-dashed border-[#d8e0eb] px-4 py-8 text-center text-sm text-[#7184a0]">
                            No patients found.
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $patients->links() }}
                </div>
            </section>

            <aside class="rounded-2xl border border-[#d8e0eb] bg-white p-4 shadow-[0_2px_8px_rgba(15,23,42,0.05)]">
                <h2 class="text-base font-medium text-[#172033]">Patient Details</h2>

                @if ($selectedPatient)
                    <div class="mt-5 text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-[#dbe8ff] text-[#2463eb]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5.121 17.804A9 9 0 1118.88 17.8M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <p class="mt-4 text-[22px] font-semibold leading-tight text-[#172033]">{{ $selectedPatient->full_name }}</p>
                        <p class="text-sm text-[#6b7280]">Patient ID: {{ $selectedPatient->patient_code }}</p>
                        <div class="mt-4 flex justify-center gap-2">
                            <a href="{{ route('patients.edit', $selectedPatient) }}" class="inline-flex items-center gap-2 rounded-lg bg-[#2463eb] px-4 py-2 text-sm font-medium text-white hover:bg-[#1f54c9]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5h2m-1 0v14m0-14L7 9m4-4l4 4" />
                                </svg>
                                Edit Patient Details
                            </a>
                        </div>
                    </div>

                    <div class="mt-5 space-y-4 border-t border-[#e5ebf3] pt-5 text-sm text-[#172033]">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-[#7184a0]">Age</p>
                            <p class="mt-1 text-sm">{{ $selectedPatient->age !== null ? $selectedPatient->age.' years old' : 'Not provided' }}</p>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-wide text-[#7184a0]">Date of Birth</p>
                            <p class="mt-1">{{ $selectedPatient->date_of_birth?->format('F d, Y') ?? 'Not provided' }}</p>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-wide text-[#7184a0]">Contact</p>
                            <div class="mt-1 space-y-1 text-[#4a5f7d]">
                                <p>{{ $selectedPatient->phone ?: 'No phone number' }}</p>
                                <p>{{ $selectedPatient->email ?: 'No email address' }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-wide text-[#7184a0]">Medical Info</p>
                            <div class="mt-1 space-y-1 text-[#4a5f7d]">
                                <p>Blood Type: {{ $selectedPatient->blood_type ?: 'Not provided' }}</p>
                                <p>Allergies: {{ $selectedPatient->allergies ?: 'Not provided' }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-wide text-[#7184a0]">Emergency Contact</p>
                            <div class="mt-1 space-y-1 text-[#4a5f7d]">
                                <p>Name: {{ $selectedPatient->emergency_contact_name ?: 'Not provided' }}</p>
                                <p>Phone: {{ $selectedPatient->emergency_contact_phone ?: 'Not provided' }}</p>
                                <p>Relationship: {{ $selectedPatient->emergency_contact_relationship ?: 'Not provided' }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-wide text-[#7184a0]">Address</p>
                            <p class="mt-1 text-[#4a5f7d]">{{ $selectedPatient->address ?: 'No address provided' }}</p>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-wide text-[#7184a0]">Gender</p>
                            <p class="mt-1 capitalize">{{ $selectedPatient->gender ?: 'Not provided' }}</p>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-wide text-[#7184a0]">Registered</p>
                            <p class="mt-1 text-[#4a5f7d]">{{ $selectedPatient->created_at?->format('F d, Y') }}</p>
                        </div>
                    </div>
                @else
                    <div class="mt-5 rounded-xl border border-dashed border-[#d8e0eb] px-4 py-10 text-center text-sm text-[#7184a0]">
                        Select a patient to view the details panel.
                    </div>
                @endif
            </aside>
        </div>
    </section>
</x-app-layout>
