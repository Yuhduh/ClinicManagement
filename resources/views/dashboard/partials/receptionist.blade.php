<section class="rounded-2xl border border-[#d8e0eb] bg-white p-5 shadow-[0_2px_8px_rgba(15,23,42,0.05)]">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl font-medium text-[#172033]">Today's Queue</h2>
        <a href="{{ route('appointments.index') }}" class="rounded-lg bg-[#2463eb] px-4 py-2 text-sm font-medium text-white hover:bg-[#1f54c9]">Open Appointments</a>
    </div>

    <div class="space-y-3">
        @forelse (($todayQueue ?? collect()) as $appointment)
            <div class="rounded-xl border border-[#d8e0eb] p-3.5">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-lg font-medium text-[#172033]">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                    <span class="text-sm text-[#4a5f7d]">{{ $appointment->scheduled_at->format('h:i A') }}</span>
                </div>
                <p class="text-sm text-[#4a5f7d]">Dr. {{ $appointment->doctor->name }} · {{ ucfirst($appointment->status) }}</p>
            </div>
        @empty
            <p class="text-sm text-[#4a5f7d]">No appointments in queue.</p>
        @endforelse
    </div>
</section>
