<x-app-layout>
    @php
        $maxRevenue = max(1, collect($monthlyRevenue)->max('amount'));
        $maxAgeCount = max(1, collect($patientAnalytics['ageGroups'])->max('count'));
        $maxTypeCount = max(1, collect($appointmentAnalytics['types'])->max('count') ?? 1);
    @endphp

    <section class="space-y-5 rounded-2xl bg-[#edf2f9] p-5">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-[28px] font-semibold leading-tight text-[#172033]">Reports &amp; Analytics</h1>
                <p class="text-sm text-[#4a5f7d]">Generate and view system reports</p>
            </div>

            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-3">
                    <input type="hidden" name="tab" value="{{ $filters['tab'] }}">
                    <select name="days" onchange="this.form.submit()" class="rounded-xl border border-[#d7deea] bg-white px-3 py-2 text-sm text-[#334155] focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]">
                        <option value="7" @selected($filters['days'] === 7)>Last 7 Days</option>
                        <option value="30" @selected($filters['days'] === 30)>Last 30 Days</option>
                        <option value="90" @selected($filters['days'] === 90)>Last 90 Days</option>
                        <option value="365" @selected($filters['days'] === 365)>Last 12 Months</option>
                    </select>
                </form>

                <a href="{{ route('reports.download', ['tab' => $filters['tab'], 'days' => $filters['days'], 'format' => 'pdf']) }}" class="inline-flex items-center gap-2 rounded-xl bg-[#050924] px-4 py-2 text-sm font-semibold text-white hover:bg-[#0d1237]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v12m0 0l4-4m-4 4l-4-4m-4 8h16" />
                    </svg>
                    <span>Download PDF</span>
                </a>

                <a href="{{ route('reports.download', ['tab' => $filters['tab'], 'days' => $filters['days'], 'format' => 'csv']) }}" class="inline-flex items-center gap-2 rounded-xl border border-[#d7deea] bg-white px-4 py-2 text-sm font-semibold text-[#334155] hover:bg-[#f8fbff]">
                    <span>CSV</span>
                </a>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            @foreach ($tabs as $tabKey => $tabLabel)
                <a href="{{ route('reports.index', ['tab' => $tabKey, 'days' => $filters['days']]) }}" class="inline-flex items-center rounded-xl border px-5 py-3 text-sm font-semibold transition {{ $filters['tab'] === $tabKey ? 'border-[#2463eb] bg-[#e7f0ff] text-[#2463eb]' : 'border-[#d3dce9] bg-white text-[#445a78] hover:bg-[#f8fbff]' }}">
                    {{ $tabLabel }}
                </a>
            @endforeach
        </div>

        @if ($filters['tab'] === 'overview')
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-2xl border border-[#d8e0eb] bg-white p-6">
                    <p class="text-sm text-[#4a5f7d]">Total Patients</p>
                    <p class="mt-4 text-4xl font-semibold text-[#172033]">{{ $overview['totalPatients'] }}</p>
                    <p class="mt-2 text-sm text-[#198754]">
                        @if (! is_null($overview['changePatients']))
                            {{ $overview['changePatients'] > 0 ? '↑' : '↓' }} {{ abs($overview['changePatients']) }}% from previous period
                        @else
                            New baseline period
                        @endif
                    </p>
                </article>

                <article class="rounded-2xl border border-[#d8e0eb] bg-white p-6">
                    <p class="text-sm text-[#4a5f7d]">Appointments</p>
                    <p class="mt-4 text-4xl font-semibold text-[#172033]">{{ $overview['appointments'] }}</p>
                    <p class="mt-2 text-sm text-[#198754]">
                        @if (! is_null($overview['changeAppointments']))
                            {{ $overview['changeAppointments'] > 0 ? '↑' : '↓' }} {{ abs($overview['changeAppointments']) }}% from previous period
                        @else
                            New baseline period
                        @endif
                    </p>
                </article>

                <article class="rounded-2xl border border-[#d8e0eb] bg-white p-6">
                    <p class="text-sm text-[#4a5f7d]">Prescriptions</p>
                    <p class="mt-4 text-4xl font-semibold text-[#172033]">{{ $overview['prescriptions'] }}</p>
                    <p class="mt-2 text-sm text-[#198754]">
                        @if (! is_null($overview['changePrescriptions']))
                            {{ $overview['changePrescriptions'] > 0 ? '↑' : '↓' }} {{ abs($overview['changePrescriptions']) }}% from previous period
                        @else
                            New baseline period
                        @endif
                    </p>
                </article>

                <article class="rounded-2xl border border-[#d8e0eb] bg-white p-6">
                    <p class="text-sm text-[#4a5f7d]">Active Users</p>
                    <p class="mt-4 text-4xl font-semibold text-[#172033]">{{ $overview['activeUsers'] }}</p>
                    <p class="mt-2 text-sm text-[#4a5f7d]">System users</p>
                </article>
            </div>

            <article class="rounded-2xl border border-[#d8e0eb] bg-white p-6">
                <h2 class="text-2xl font-semibold text-[#172033]">Revenue Trend (Estimated)</h2>
                <p class="text-sm text-[#7184a0]">Based on appointment type rates in the system.</p>
                <div class="mt-6 space-y-4">
                    @foreach ($monthlyRevenue as $row)
                        <div class="grid grid-cols-[64px_1fr] items-center gap-4">
                            <p class="text-sm font-medium text-[#4a5f7d]">{{ $row['month'] }}</p>
                            <div class="h-9 rounded-full bg-[#e9eef7] p-1">
                                <div class="flex h-full items-center justify-end rounded-full bg-gradient-to-r from-[#3f82ec] to-[#215ce8] pr-4 text-sm font-semibold text-white" style="width: {{ max(6, round(($row['amount'] / $maxRevenue) * 100, 2)) }}%">
                                    ${{ number_format($row['amount']) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>
        @endif

        @if ($filters['tab'] === 'patients')
            <div class="grid gap-5 xl:grid-cols-[1.1fr_0.9fr]">
                <article class="rounded-2xl border border-[#d8e0eb] bg-white p-6">
                    <h2 class="text-2xl font-semibold text-[#172033]">Patients by Age Group</h2>
                    <div class="mt-6 space-y-5">
                        @foreach ($patientAnalytics['ageGroups'] as $group)
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm text-[#4a5f7d]">
                                    <p class="font-medium">{{ $group['label'] }}</p>
                                    <p>{{ $group['count'] }} patients</p>
                                </div>
                                <div class="h-2 rounded-full bg-[#e9eef7]">
                                    <div class="h-full rounded-full bg-[#2463eb]" style="width: {{ max(4, round(($group['count'] / $maxAgeCount) * 100, 2)) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="space-y-3 rounded-2xl border border-[#d8e0eb] bg-white p-6">
                    <h2 class="text-2xl font-semibold text-[#172033]">Patient Growth</h2>

                    <div class="rounded-2xl bg-[#edf3ff] p-4">
                        <p class="text-sm text-[#4a5f7d]">New Patients (Selected Range)</p>
                        <p class="mt-1 text-4xl font-semibold text-[#172033]">{{ $patientAnalytics['newPatients'] }}</p>
                    </div>

                    <div class="rounded-2xl bg-[#e7f5eb] p-4">
                        <p class="text-sm text-[#4a5f7d]">Returning Patients</p>
                        <p class="mt-1 text-4xl font-semibold text-[#172033]">{{ $patientAnalytics['returningPatients'] }}</p>
                    </div>

                    <div class="rounded-2xl border border-[#d8e0eb] p-4">
                        <p class="mb-3 text-sm font-medium text-[#4a5f7d]">Gender Distribution</p>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach ($patientAnalytics['genderDistribution'] as $row)
                                <div>
                                    <p class="text-3xl font-semibold text-[#172033]">{{ $row['percent'] }}%</p>
                                    <p class="text-sm text-[#4a5f7d]">{{ $row['label'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </article>
            </div>
        @endif

        @if ($filters['tab'] === 'appointments')
            <div class="grid gap-5 xl:grid-cols-[1.1fr_0.9fr]">
                <article class="rounded-2xl border border-[#d8e0eb] bg-white p-6">
                    <h2 class="text-2xl font-semibold text-[#172033]">Appointments by Type</h2>
                    <div class="mt-6 space-y-5">
                        @forelse ($appointmentAnalytics['types'] as $type)
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm text-[#4a5f7d]">
                                    <p class="font-medium text-[#253753]">{{ $type['type'] }}</p>
                                    <p>{{ $type['count'] }} appointments</p>
                                </div>
                                <div class="h-2 rounded-full bg-[#eee9fd]">
                                    <div class="h-full rounded-full bg-[#8a26f0]" style="width: {{ max(4, round(($type['count'] / $maxTypeCount) * 100, 2)) }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-[#7184a0]">No appointments in the selected range.</p>
                        @endforelse
                    </div>
                </article>

                <article class="space-y-3 rounded-2xl border border-[#d8e0eb] bg-white p-6">
                    <h2 class="text-2xl font-semibold text-[#172033]">Appointment Status</h2>

                    <div class="rounded-2xl bg-[#e7f5eb] p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#4a5f7d]">Confirmed</p>
                                <p class="text-4xl font-semibold text-[#172033]">{{ $appointmentAnalytics['statusCards']['confirmed']['count'] }}</p>
                            </div>
                            <p class="text-lg font-semibold text-[#198754]">{{ $appointmentAnalytics['statusCards']['confirmed']['percent'] }}%</p>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-[#f8f2df] p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#4a5f7d]">Pending</p>
                                <p class="text-4xl font-semibold text-[#172033]">{{ $appointmentAnalytics['statusCards']['pending']['count'] }}</p>
                            </div>
                            <p class="text-lg font-semibold text-[#ae7c0a]">{{ $appointmentAnalytics['statusCards']['pending']['percent'] }}%</p>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-[#fdebec] p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#4a5f7d]">Cancelled</p>
                                <p class="text-4xl font-semibold text-[#172033]">{{ $appointmentAnalytics['statusCards']['cancelled']['count'] }}</p>
                            </div>
                            <p class="text-lg font-semibold text-[#c43e3e]">{{ $appointmentAnalytics['statusCards']['cancelled']['percent'] }}%</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-[#d8e0eb] p-4">
                        <p class="text-sm text-[#4a5f7d]">Daily Average</p>
                        <p class="text-4xl font-semibold text-[#172033]">{{ number_format($appointmentAnalytics['dailyAverage'], 1) }}</p>
                        <p class="text-sm text-[#4a5f7d]">appointments per day</p>
                    </div>
                </article>
            </div>
        @endif

        @if ($filters['tab'] === 'revenue')
            <article class="rounded-2xl border border-[#d8e0eb] bg-white p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-2xl font-semibold text-[#172033]">Revenue Trend (Estimated)</h2>
                        <p class="text-sm text-[#7184a0]">Approximate revenue based on appointment type rates.</p>
                    </div>
                    <p class="text-sm font-semibold text-[#4a5f7d]">Total: ${{ number_format(collect($monthlyRevenue)->sum('amount')) }}</p>
                </div>

                <div class="mt-6 space-y-4">
                    @foreach ($monthlyRevenue as $row)
                        <div class="grid grid-cols-[64px_1fr] items-center gap-4">
                            <p class="text-sm font-medium text-[#4a5f7d]">{{ $row['month'] }}</p>
                            <div class="h-9 rounded-full bg-[#e9eef7] p-1">
                                <div class="flex h-full items-center justify-end rounded-full bg-gradient-to-r from-[#3f82ec] to-[#215ce8] pr-4 text-sm font-semibold text-white" style="width: {{ max(6, round(($row['amount'] / $maxRevenue) * 100, 2)) }}%">
                                    ${{ number_format($row['amount']) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>
        @endif

        @if ($filters['tab'] === 'activity')
            <article class="rounded-2xl border border-[#d8e0eb] bg-white p-6">
                <h2 class="text-2xl font-semibold text-[#172033]">User Activity</h2>

                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#d8e0eb] text-sm">
                        <thead class="text-left text-[#4a5f7d]">
                            <tr>
                                <th class="px-4 py-2.5 font-medium">User</th>
                                <th class="px-4 py-2.5 font-medium">Role</th>
                                <th class="px-4 py-2.5 font-medium">Status</th>
                                <th class="px-4 py-2.5 font-medium">Last Updated</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#edf2f9]">
                            @forelse ($activityRows as $user)
                                <tr>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-[#172033]">{{ $user->name }}</p>
                                        <p class="text-[#4a5f7d]">{{ $user->email }}</p>
                                    </td>
                                    <td class="px-4 py-3 capitalize text-[#4a5f7d]">{{ $user->role }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $user->is_active ? 'bg-[#dff6ea] text-[#198754]' : 'bg-[#fde8e8] text-[#dc3545]' }}">{{ $user->is_active ? 'active' : 'inactive' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-[#4a5f7d]">{{ optional($user->updated_at)->toDateString() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-[#7184a0]">No user activity data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        @endif
    </section>
</x-app-layout>
