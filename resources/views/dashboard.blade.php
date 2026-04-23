<x-app-layout>
    <div class="space-y-5 rounded-2xl bg-[#edf2f9] p-5">
        <div>
            <h1 class="text-[28px] font-semibold leading-tight text-[#172033]">Dashboard</h1>
            <p class="mt-1 text-sm text-[#4a5f7d]">Welcome back, {{ auth()->user()->name }}</p>
        </div>

        <div class="grid max-w-7xl gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-[#d8e0eb] bg-white p-4 shadow-[0_2px_8px_rgba(15,23,42,0.05)]">
                <p class="text-xs text-[#4a5f7d]">Today's Appointments</p>
                <p class="mt-1.5 text-[28px] font-semibold leading-none text-[#172033]">{{ $stats['today_appointments'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-[#d8e0eb] bg-white p-4 shadow-[0_2px_8px_rgba(15,23,42,0.05)]">
                <p class="text-xs text-[#4a5f7d]">Pending Prescriptions</p>
                <p class="mt-1.5 text-[28px] font-semibold leading-none text-[#172033]">{{ $stats['pending_prescriptions'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-[#d8e0eb] bg-white p-4 shadow-[0_2px_8px_rgba(15,23,42,0.05)]">
                <p class="text-xs text-[#4a5f7d]">New Patients Today</p>
                <p class="mt-1.5 text-[28px] font-semibold leading-none text-[#172033]">{{ $stats['new_patients_today'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-[#d8e0eb] bg-white p-4 shadow-[0_2px_8px_rgba(15,23,42,0.05)]">
                <p class="text-xs text-[#4a5f7d]">Role Metric</p>
                <p class="mt-1.5 text-[28px] font-semibold leading-none text-[#172033]">{{ $stats['active_staff'] ?? $stats['my_patients'] ?? $stats['registered_patients'] ?? 0 }}</p>
            </div>
        </div>

        @if (auth()->user()->role === 'admin')
            @include('dashboard.partials.admin')
        @elseif (auth()->user()->role === 'doctor')
            @include('dashboard.partials.doctor')
        @else
            @include('dashboard.partials.receptionist')
        @endif
    </div>
</x-app-layout>
