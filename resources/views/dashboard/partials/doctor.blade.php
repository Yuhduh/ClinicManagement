<div class="grid gap-6 xl:grid-cols-2">
    <section class="rounded-2xl border border-[#d8e0eb] bg-white p-5 shadow-[0_2px_8px_rgba(15,23,42,0.05)]">
        <h2 class="mb-4 text-xl font-medium text-[#172033]">Today's Appointments</h2>
        <div class="space-y-3">
            @forelse (($todayAppointments ?? collect()) as $appointment)
                <div class="rounded-xl border border-[#d8e0eb] p-3.5">
                    <p class="text-lg font-medium text-[#172033]">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                    <p class="text-sm text-[#4a5f7d]">{{ $appointment->scheduled_at->format('h:i A') }} · {{ ucfirst($appointment->status) }}</p>
                </div>
            @empty
                <p class="text-sm text-[#4a5f7d]">No appointments for today.</p>
            @endforelse
        </div>
    </section>

    <section class="rounded-2xl border border-[#d8e0eb] bg-white p-5 shadow-[0_2px_8px_rgba(15,23,42,0.05)]">
        <h2 class="mb-4 text-xl font-medium text-[#172033]">Recent Patient Records</h2>
        <div class="space-y-3">
            @forelse (($recentRecords ?? collect()) as $record)
                <div class="rounded-xl border border-[#d8e0eb] p-3.5">
                    <p class="text-lg font-medium text-[#172033]">{{ $record->patient->first_name }} {{ $record->patient->last_name }}</p>
                    <p class="text-sm text-[#4a5f7d]">{{ $record->visit_date->format('M d, Y') }} · {{ $record->diagnosis ?: 'No diagnosis' }}</p>
                </div>
            @empty
                <p class="text-sm text-[#4a5f7d]">No records yet.</p>
            @endforelse
        </div>
    </section>
</div>
