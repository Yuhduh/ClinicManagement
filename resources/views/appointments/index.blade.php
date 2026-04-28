<x-app-layout>
    <section class="space-y-5 rounded-2xl bg-[#edf2f9] p-5">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-[28px] font-semibold leading-tight text-[#172033]">Appointments</h1>
                <p class="text-sm text-[#4a5f7d]">Manage and schedule patient appointments</p>
            </div>
            <a href="{{ route('appointments.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#2463eb] px-4 py-2 text-sm font-medium text-white hover:bg-[#1f54c9]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5v14m-7-7h14" />
                </svg>
                Create Appointment
            </a>
        </div>

        <form method="GET" action="{{ route('appointments.index') }}" class="rounded-2xl border border-[#d8e0eb] bg-white p-3.5">
            <div class="flex items-center gap-3">
                <label class="relative block flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-[#8aa0bf]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search appointments..." class="w-full rounded-xl border border-[#d7deea] bg-[#f8fbff] py-2.5 pl-10 pr-3 text-sm text-[#334155] placeholder:text-[#94a3b8] focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
                </label>
                <input type="hidden" name="view" value="{{ $viewMode }}" />
            </div>
        </form>

        <div class="inline-flex rounded-full border border-[#d8e0eb] bg-white p-1 shadow-sm">
            <a href="{{ route('appointments.index', ['view' => 'list', 'search' => $search]) }}" class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold {{ $viewMode === 'list' ? 'bg-[#edf2f9] text-[#172033]' : 'text-[#4a5f7d]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" /></svg>
                List View
            </a>
            <a href="{{ route('appointments.index', ['view' => 'calendar', 'search' => $search]) }}" class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold {{ $viewMode === 'calendar' ? 'bg-[#edf2f9] text-[#172033]' : 'text-[#4a5f7d]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Calendar View
            </a>
        </div>

        @if ($viewMode === 'calendar')
            <div class="space-y-6">
                @forelse ($calendarAppointments as $group)
                    <section class="rounded-2xl border border-[#d8e0eb] bg-white p-5 shadow-[0_2px_8px_rgba(15,23,42,0.05)]">
                        <h2 class="text-lg font-semibold text-[#172033]">{{ $group['date']->format('l, F j, Y') }}</h2>
                        <div class="mt-4 space-y-3">
                            @foreach ($group['items'] as $appointment)
                                <div class="flex items-center gap-4 rounded-xl border border-[#d8e0eb] bg-[#fbfdff] p-4">
                                    <div class="w-16 shrink-0 text-center">
                                        <p class="text-lg font-semibold text-[#172033]">{{ $appointment->scheduled_at->format('H:i') }}</p>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-base font-semibold text-[#172033]">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                                        <p class="truncate text-sm text-[#4a5f7d]">{{ $appointment->type }} - Dr. {{ $appointment->doctor->display_name }}</p>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs font-medium {{ $appointment->status === 'confirmed' ? 'bg-[#dff6ea] text-[#198754]' : ($appointment->status === 'pending' ? 'bg-[#fff4db] text-[#e58a00]' : 'bg-[#fde8e8] text-[#dc3545]') }}">{{ ucfirst($appointment->status) }}</span>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('appointments.edit', $appointment) }}" class="rounded-lg border border-[#d7deea] bg-white px-3 py-1.5 text-sm font-medium text-[#4a5f7d] hover:bg-[#f8fbff]">Edit</a>
                                        <button 
                                            type="button"
                                            data-confirm-delete 
                                            data-confirm-modal="delete-appointment-{{ $appointment->id }}"
                                            data-confirm-message="Are you sure you want to delete this appointment? This action cannot be undone."
                                            class="rounded-lg border border-[#f5c2c7] bg-[#fde8e8] px-3 py-1.5 text-sm font-medium text-[#dc3545] hover:bg-[#fbd5d9]"
                                        >
                                            Delete
                                        </button>
                                        <form id="delete-form-{{ $appointment->id }}" method="POST" action="{{ route('appointments.destroy', $appointment) }}" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <x-confirm-modal 
                                            :id="'delete-appointment-' . $appointment->id"
                                            title="Delete Appointment"
                                            message="Are you sure you want to delete this appointment? This action cannot be undone."
                                            confirmText="Delete"
                                            cancelText="Cancel"
                                        />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @empty
                    <div class="rounded-2xl border border-dashed border-[#d8e0eb] bg-white px-4 py-10 text-center text-sm text-[#7184a0]">
                        No appointments found.
                    </div>
                @endforelse
            </div>
        @else
            <div class="overflow-hidden rounded-2xl border border-[#d8e0eb] bg-white shadow-[0_2px_8px_rgba(15,23,42,0.05)]">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#d8e0eb] text-sm">
                        <thead class="bg-[#f8fbff] text-left text-[#4a5f7d]">
                            <tr>
                                <th class="px-4 py-2.5 font-medium">Patient</th>
                                <th class="px-4 py-2.5 font-medium">Doctor</th>
                                <th class="px-4 py-2.5 font-medium">Schedule</th>
                                <th class="px-4 py-2.5 font-medium">Status</th>
                                <th class="px-4 py-2.5 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#d8e0eb] bg-white">
                            @forelse ($appointments as $appointment)
                                <tr class="odd:bg-white even:bg-[#fbfdff]">
                                    <td class="px-4 py-2.5 text-[#172033]">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                                    <td class="px-4 py-2.5 text-[#4a5f7d]">{{ $appointment->doctor->display_name }}</td>
                                    <td class="px-4 py-2.5 text-[#4a5f7d]">{{ $appointment->scheduled_at->format('M d, Y h:i A') }}</td>
                                    <td class="px-4 py-2.5">
                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $appointment->status === 'confirmed' ? 'bg-[#dff6ea] text-[#198754]' : ($appointment->status === 'pending' ? 'bg-[#fff4db] text-[#e58a00]' : 'bg-[#fde8e8] text-[#dc3545]') }}">{{ ucfirst($appointment->status) }}</span>
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('appointments.edit', $appointment) }}" class="rounded-lg border border-[#d7deea] bg-white px-3 py-1.5 text-sm font-medium text-[#4a5f7d] hover:bg-[#f8fbff]">View</a>
                                            <a href="{{ route('appointments.edit', $appointment) }}" class="rounded-lg border border-[#b8cef8] bg-[#eef4ff] px-3 py-1.5 text-sm font-medium text-[#2463eb] hover:bg-[#e1ebff]">Edit</a>
                                            <button 
                                                type="button"
                                                data-confirm-delete 
                                                data-confirm-modal="delete-appointment-{{ $appointment->id }}"
                                                data-confirm-message="Are you sure you want to delete this appointment? This action cannot be undone."
                                                class="rounded-lg border border-[#f5c2c7] bg-[#fde8e8] px-3 py-1.5 text-sm font-medium text-[#dc3545] hover:bg-[#fbd5d9]"
                                            >
                                                Delete
                                            </button>
                                            <form id="delete-form-{{ $appointment->id }}" method="POST" action="{{ route('appointments.destroy', $appointment) }}" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <x-confirm-modal 
                                                :id="'delete-appointment-' . $appointment->id"
                                                title="Delete Appointment"
                                                message="Are you sure you want to delete this appointment? This action cannot be undone."
                                                confirmText="Delete"
                                                cancelText="Cancel"
                                            />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">No appointments found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $appointments->links() }}
            </div>
        @endif
    </section>
</x-app-layout>
