<x-app-layout>
    <section class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Prescriptions</h1>
                <p class="text-xs text-slate-500">Create and manage patient prescriptions</p>
            </div>
            <a href="{{ route('prescriptions.create') }}" class="rounded-lg bg-[#2463eb] px-4 py-2 text-sm font-medium text-white hover:bg-[#1f54c9]">+ Create Prescription</a>
        </div>

        <div class="space-y-4">
            @forelse ($prescriptions as $prescription)
                <div class="rounded-2xl border border-[#e5e9f1] bg-white shadow-sm p-6 space-y-4">
                    <!-- Header -->
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">{{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}</h2>
                            <p class="text-sm text-slate-500">Prescribed by {{ $prescription->doctor->display_name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500 uppercase tracking-wide">Prescription ID</p>
                            <p class="text-lg font-semibold text-slate-900">{{ $prescription->reference }}</p>
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="flex items-center gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $prescription->created_at->format('F j, Y') }}
                    </div>

                    <!-- Medications -->
                    <div class="border-t border-[#e5e9f1] pt-4">
                        <h3 class="text-sm font-semibold text-slate-900 mb-3">Medications</h3>
                        
                        <div class="rounded-xl border border-[#e5e9f1] bg-[#fbfdff] p-4 space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-6 h-6 rounded-full bg-[#d8e0eb] flex items-center justify-center mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-[#2463eb]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900">{{ $prescription->medication_name }}</p>
                                    <div class="grid grid-cols-3 gap-4 mt-3">
                                        <div>
                                            <p class="text-xs text-slate-500 font-medium">Dosage</p>
                                            <p class="text-sm font-medium text-slate-900">{{ $prescription->dosage }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500 font-medium">Frequency</p>
                                            <p class="text-sm font-medium text-slate-900">{{ $prescription->frequency }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500 font-medium">Duration</p>
                                            <p class="text-sm font-medium text-slate-900">{{ $prescription->duration ?? '—' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if ($prescription->notes)
                        <div class="border-t border-[#e5e9f1] pt-4">
                            <h3 class="text-sm font-semibold text-slate-900 mb-2">Notes</h3>
                            <p class="text-sm text-slate-600">{{ $prescription->notes }}</p>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="border-t border-[#e5e9f1] pt-4 flex gap-2">
                        <a href="{{ route('prescriptions.edit', $prescription) }}" class="flex-1 rounded-lg bg-[#2463eb] px-3 py-2 text-center text-sm font-medium text-white hover:bg-[#1f54c9]">
                            Edit
                        </a>
                        <button
                            type="button"
                            data-confirm-delete
                            data-confirm-modal="delete-prescription-{{ $prescription->id }}"
                            data-confirm-message="Are you sure you want to delete this prescription? This action cannot be undone."
                            class="flex-1 rounded-lg border border-[#e5e9f1] px-3 py-2 text-center text-sm font-medium text-slate-600 hover:bg-[#f8fbff]"
                        >
                            Delete
                        </button>
                        <form id="delete-form-{{ $prescription->id }}" method="POST" action="{{ route('prescriptions.destroy', $prescription) }}" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                        <x-confirm-modal 
                            :id="'delete-prescription-' . $prescription->id"
                            title="Delete Prescription"
                            message="Are you sure you want to delete this prescription? This action cannot be undone."
                            confirmText="Delete"
                            cancelText="Cancel"
                        />
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-[#e5e9f1] bg-white shadow-sm p-12 text-center">
                    <p class="text-slate-500">No prescriptions found</p>
                </div>
            @endforelse

            {{ $prescriptions->links() }}
        </div>
    </section>

<!-- Success Modal -->
@if (session('prescriptionSuccess'))
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
                <h3 class="text-lg font-semibold text-slate-900">Prescription Saved</h3>
            </div>
            <p class="text-sm text-slate-600 mb-6">The prescription has been successfully created.</p>
            <button @click="open = false" class="w-full rounded-lg bg-[#2463eb] px-4 py-2 text-sm font-medium text-white hover:bg-[#1f54c9]">
                Done
            </button>
        </div>
    </div>
@endif
</x-app-layout>
