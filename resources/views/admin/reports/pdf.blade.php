<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Clinic Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.4;
            margin: 24px;
        }

        h1 {
            font-size: 22px;
            margin: 0;
            color: #0f172a;
        }

        h2 {
            font-size: 16px;
            margin: 20px 0 8px;
            color: #0f172a;
        }

        .sub {
            color: #475569;
            margin: 4px 0 0;
        }

        .meta {
            margin-top: 12px;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #f8fafc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th, td {
            border: 1px solid #dbe3ee;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f1f5f9;
            color: #334155;
        }

        .grid {
            width: 100%;
            margin-top: 8px;
        }

        .card {
            width: 48%;
            display: inline-block;
            margin: 0 1% 10px 0;
            border: 1px solid #dbe3ee;
            border-radius: 8px;
            padding: 10px;
            box-sizing: border-box;
        }

        .value {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 4px;
        }

        .muted {
            color: #64748b;
        }
    </style>
</head>
<body>
    <h1>Clinic Management Report</h1>
    <p class="sub">{{ \Illuminate\Support\Str::title($tab === 'patients' ? 'patient analytics' : str_replace('_', ' ', $tab)) }}</p>

    <div class="meta">
        <strong>Date Range:</strong> {{ $startDate->toDateString() }} to {{ $endDate->toDateString() }}<br>
        <strong>Period:</strong> Last {{ $days }} days<br>
        <strong>Generated:</strong> {{ now()->format('Y-m-d H:i:s') }}
    </div>

    @if ($tab === 'overview')
        <h2>Overview</h2>
        <div class="grid">
            <div class="card">
                <div class="muted">Total Patients</div>
                <div class="value">{{ $overview['totalPatients'] }}</div>
            </div>
            <div class="card">
                <div class="muted">Appointments</div>
                <div class="value">{{ $overview['appointments'] }}</div>
            </div>
            <div class="card">
                <div class="muted">Prescriptions</div>
                <div class="value">{{ $overview['prescriptions'] }}</div>
            </div>
            <div class="card">
                <div class="muted">Active Users</div>
                <div class="value">{{ $overview['activeUsers'] }}</div>
            </div>
        </div>

        <h2>Estimated Revenue Trend</h2>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Estimated Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($monthlyRevenue as $row)
                    <tr>
                        <td>{{ $row['month'] }}</td>
                        <td>${{ number_format($row['amount']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if ($tab === 'patients')
        <h2>Patients by Age Group</h2>
        <table>
            <thead>
                <tr>
                    <th>Age Group</th>
                    <th>Patients</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($patientAnalytics['ageGroups'] as $group)
                    <tr>
                        <td>{{ $group['label'] }}</td>
                        <td>{{ $group['count'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2>Patient Growth</h2>
        <table>
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>New Patients (Selected Range)</td>
                    <td>{{ $patientAnalytics['newPatients'] }}</td>
                </tr>
                <tr>
                    <td>Returning Patients</td>
                    <td>{{ $patientAnalytics['returningPatients'] }}</td>
                </tr>
            </tbody>
        </table>

        <h2>Gender Distribution</h2>
        <table>
            <thead>
                <tr>
                    <th>Gender</th>
                    <th>Percent</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($patientAnalytics['genderDistribution'] as $row)
                    <tr>
                        <td>{{ $row['label'] }}</td>
                        <td>{{ $row['percent'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if ($tab === 'appointments')
        <h2>Appointments by Type</h2>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Appointments</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointmentAnalytics['types'] as $type)
                    <tr>
                        <td>{{ $type['type'] }}</td>
                        <td>{{ $type['count'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2>Appointment Status</h2>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Percent</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointmentAnalytics['statusCards'] as $status => $data)
                    <tr>
                        <td>{{ \Illuminate\Support\Str::title($status) }}</td>
                        <td>{{ $data['count'] }}</td>
                        <td>{{ $data['percent'] }}%</td>
                    </tr>
                @endforeach
                <tr>
                    <td>Daily Average</td>
                    <td colspan="2">{{ number_format($appointmentAnalytics['dailyAverage'], 1) }} appointments/day</td>
                </tr>
            </tbody>
        </table>
    @endif

    @if ($tab === 'revenue')
        <h2>Estimated Revenue Trend</h2>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Estimated Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($monthlyRevenue as $row)
                    <tr>
                        <td>{{ $row['month'] }}</td>
                        <td>${{ number_format($row['amount']) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong>${{ number_format(collect($monthlyRevenue)->sum('amount')) }}</strong></td>
                </tr>
            </tbody>
        </table>
    @endif

    @if ($tab === 'activity')
        <h2>User Activity</h2>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activityRows as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ \Illuminate\Support\Str::title($user->role) }}</td>
                        <td>{{ $user->is_active ? 'active' : 'inactive' }}</td>
                        <td>{{ optional($user->updated_at)->toDateString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
