<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use Carbon\CarbonInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    /**
     * @var array<string, int>
     */
    private array $appointmentRates = [
        'General Consultation' => 100,
        'Annual Check-up' => 140,
        'Specialist Consultation' => 180,
        'Follow-up' => 90,
        'Lab Results Review' => 80,
    ];

    public function index(Request $request): View
    {
        [$days, $tab, $startDate, $endDate] = $this->resolveFilters($request);

        $previousStart = $startDate->copy()->subDays($days);
        $previousEnd = $startDate->copy()->subSecond();

        $overview = $this->buildOverviewMetrics($startDate, $endDate, $previousStart, $previousEnd);
        $monthlyRevenue = $this->buildMonthlyRevenue();
        $patientAnalytics = $this->buildPatientAnalytics($startDate, $endDate);
        $appointmentAnalytics = $this->buildAppointmentAnalytics($startDate, $endDate, $days);
        $activityRows = User::query()
            ->latest('updated_at')
            ->take(12)
            ->get(['name', 'email', 'role', 'is_active', 'updated_at']);

        return view('admin.reports.index', [
            'filters' => [
                'days' => $days,
                'tab' => $tab,
            ],
            'tabs' => [
                'overview' => 'Overview',
                'patients' => 'Patient Analytics',
                'appointments' => 'Appointments',
                'revenue' => 'Revenue',
                'activity' => 'System Activity',
            ],
            'overview' => $overview,
            'monthlyRevenue' => $monthlyRevenue,
            'patientAnalytics' => $patientAnalytics,
            'appointmentAnalytics' => $appointmentAnalytics,
            'activityRows' => $activityRows,
        ]);
    }

    public function download(Request $request): Response
    {
        [$days, $tab, $startDate, $endDate] = $this->resolveFilters($request);
        $format = $request->string('format', 'csv')->lower()->value();

        if ($format === 'pdf') {
            return $this->downloadPdf($tab, $days, $startDate, $endDate);
        }

        $filename = sprintf('report-%s-%s-days-%s.csv', $tab, $days, now()->format('Ymd_His'));

        return response()->streamDownload(function () use ($tab, $days, $startDate, $endDate): void {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            fputcsv($handle, ['Clinic Management System']);
            fputcsv($handle, ['Report', Str::title(str_replace('_', ' ', $tab))]);
            fputcsv($handle, ['Range', sprintf('%s to %s', $startDate->toDateString(), $endDate->toDateString())]);
            fputcsv($handle, ['Days', (string) $days]);
            fputcsv($handle, []);

            switch ($tab) {
                case 'patients':
                    $patientAnalytics = $this->buildPatientAnalytics($startDate, $endDate);

                    fputcsv($handle, ['Patients by Age Group']);
                    fputcsv($handle, ['Age Group', 'Patients']);
                    foreach ($patientAnalytics['ageGroups'] as $group) {
                        fputcsv($handle, [$group['label'], $group['count']]);
                    }

                    fputcsv($handle, []);
                    fputcsv($handle, ['Gender Distribution']);
                    fputcsv($handle, ['Gender', 'Percent']);
                    foreach ($patientAnalytics['genderDistribution'] as $row) {
                        fputcsv($handle, [$row['label'], $row['percent'].'%']);
                    }
                    break;

                case 'appointments':
                    $appointmentAnalytics = $this->buildAppointmentAnalytics($startDate, $endDate, $days);

                    fputcsv($handle, ['Appointments by Type']);
                    fputcsv($handle, ['Type', 'Appointments']);
                    foreach ($appointmentAnalytics['types'] as $row) {
                        fputcsv($handle, [$row['type'], $row['count']]);
                    }

                    fputcsv($handle, []);
                    fputcsv($handle, ['Appointment Status']);
                    fputcsv($handle, ['Status', 'Count', 'Percent']);
                    foreach ($appointmentAnalytics['statusCards'] as $status => $data) {
                        fputcsv($handle, [Str::title($status), $data['count'], $data['percent'].'%']);
                    }

                    fputcsv($handle, []);
                    fputcsv($handle, ['Daily Average', number_format($appointmentAnalytics['dailyAverage'], 2)]);
                    break;

                case 'revenue':
                    $monthlyRevenue = $this->buildMonthlyRevenue();

                    fputcsv($handle, ['Estimated Revenue Trend']);
                    fputcsv($handle, ['Month', 'Estimated Revenue']);
                    foreach ($monthlyRevenue as $row) {
                        fputcsv($handle, [$row['month'], $row['amount']]);
                    }

                    fputcsv($handle, []);
                    fputcsv($handle, ['Total Estimated Revenue', $monthlyRevenue->sum('amount')]);
                    break;

                case 'activity':
                    $users = User::query()
                        ->latest('updated_at')
                        ->get(['name', 'email', 'role', 'is_active', 'updated_at']);

                    fputcsv($handle, ['User Activity']);
                    fputcsv($handle, ['Name', 'Email', 'Role', 'Status', 'Last Updated']);
                    foreach ($users as $user) {
                        fputcsv($handle, [
                            $user->name,
                            $user->email,
                            Str::title($user->role),
                            $user->is_active ? 'active' : 'inactive',
                            optional($user->updated_at)->toDateString(),
                        ]);
                    }
                    break;

                case 'overview':
                default:
                    $overview = $this->buildOverviewMetrics(
                        $startDate,
                        $endDate,
                        $startDate->copy()->subDays($days),
                        $startDate->copy()->subSecond()
                    );

                    fputcsv($handle, ['Overview']);
                    fputcsv($handle, ['Metric', 'Value']);
                    fputcsv($handle, ['Total Patients', $overview['totalPatients']]);
                    fputcsv($handle, ['Appointments', $overview['appointments']]);
                    fputcsv($handle, ['Prescriptions', $overview['prescriptions']]);
                    fputcsv($handle, ['Active Users', $overview['activeUsers']]);

                    fputcsv($handle, []);
                    fputcsv($handle, ['Estimated Revenue Trend']);
                    fputcsv($handle, ['Month', 'Estimated Revenue']);
                    foreach ($this->buildMonthlyRevenue() as $row) {
                        fputcsv($handle, [$row['month'], $row['amount']]);
                    }
                    break;
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function downloadPdf(string $tab, int $days, CarbonInterface $startDate, CarbonInterface $endDate): Response
    {
        $previousStart = $startDate->copy()->subDays($days);
        $previousEnd = $startDate->copy()->subSecond();

        $overview = $this->buildOverviewMetrics($startDate, $endDate, $previousStart, $previousEnd);
        $monthlyRevenue = $this->buildMonthlyRevenue();
        $patientAnalytics = $this->buildPatientAnalytics($startDate, $endDate);
        $appointmentAnalytics = $this->buildAppointmentAnalytics($startDate, $endDate, $days);
        $activityRows = User::query()
            ->latest('updated_at')
            ->get(['name', 'email', 'role', 'is_active', 'updated_at']);

        $html = view('admin.reports.pdf', [
            'tab' => $tab,
            'days' => $days,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'overview' => $overview,
            'monthlyRevenue' => $monthlyRevenue,
            'patientAnalytics' => $patientAnalytics,
            'appointmentAnalytics' => $appointmentAnalytics,
            'activityRows' => $activityRows,
        ])->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = sprintf('report-%s-%s-days-%s.pdf', $tab, $days, now()->format('Ymd_His'));

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    /**
     * @return array{0:int,1:string,2:CarbonInterface,3:CarbonInterface}
     */
    private function resolveFilters(Request $request): array
    {
        $allowedDays = [7, 30, 90, 365];
        $allowedTabs = ['overview', 'patients', 'appointments', 'revenue', 'activity'];

        $days = $request->integer('days', 30);
        if (! in_array($days, $allowedDays, true)) {
            $days = 30;
        }

        $tab = $request->string('tab', 'overview')->value();
        if (! in_array($tab, $allowedTabs, true)) {
            $tab = 'overview';
        }

        $endDate = now()->endOfDay();
        $startDate = now()->copy()->subDays($days - 1)->startOfDay();

        return [$days, $tab, $startDate, $endDate];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildOverviewMetrics(
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        CarbonInterface $previousStart,
        CarbonInterface $previousEnd
    ): array {
        $totalPatients = Patient::count();

        $appointments = Appointment::whereBetween('scheduled_at', [$startDate, $endDate])->count();
        $previousAppointments = Appointment::whereBetween('scheduled_at', [$previousStart, $previousEnd])->count();

        $prescriptions = Prescription::whereBetween('created_at', [$startDate, $endDate])->count();
        $previousPrescriptions = Prescription::whereBetween('created_at', [$previousStart, $previousEnd])->count();

        $newPatients = Patient::whereBetween('created_at', [$startDate, $endDate])->count();
        $previousNewPatients = Patient::whereBetween('created_at', [$previousStart, $previousEnd])->count();

        return [
            'totalPatients' => $totalPatients,
            'appointments' => $appointments,
            'prescriptions' => $prescriptions,
            'activeUsers' => User::where('is_active', true)->count(),
            'changePatients' => $this->calculateChange($newPatients, $previousNewPatients),
            'changeAppointments' => $this->calculateChange($appointments, $previousAppointments),
            'changePrescriptions' => $this->calculateChange($prescriptions, $previousPrescriptions),
        ];
    }

    private function calculateChange(int $current, int $previous): ?float
    {
        if ($previous === 0) {
            return null;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    private function buildMonthlyRevenue(): Collection
    {
        $start = now()->copy()->startOfMonth()->subMonths(5);
        $end = now()->copy()->endOfMonth();

        $appointments = Appointment::query()
            ->whereBetween('scheduled_at', [$start, $end])
            ->get(['scheduled_at', 'type']);

        $monthLabels = collect(range(0, 5))
            ->map(fn (int $offset) => $start->copy()->addMonths($offset)->format('M'));

        return $monthLabels->map(function (string $label) use ($appointments) {
            $monthlyAppointments = $appointments->filter(
                fn (Appointment $appointment) => $appointment->scheduled_at?->format('M') === $label
            );

            $amount = $monthlyAppointments->sum(function (Appointment $appointment): int {
                return $this->appointmentRates[$appointment->type] ?? 100;
            });

            return [
                'month' => $label,
                'amount' => $amount,
            ];
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPatientAnalytics(CarbonInterface $startDate, CarbonInterface $endDate): array
    {
        $ages = Patient::query()
            ->whereNotNull('date_of_birth')
            ->get(['date_of_birth'])
            ->map(fn (Patient $patient) => optional($patient->date_of_birth)->age)
            ->filter(fn (?int $age) => is_int($age));

        $ageGroups = [
            ['label' => '0-18 years', 'count' => $ages->filter(fn (int $age) => $age <= 18)->count()],
            ['label' => '19-35 years', 'count' => $ages->filter(fn (int $age) => $age >= 19 && $age <= 35)->count()],
            ['label' => '36-50 years', 'count' => $ages->filter(fn (int $age) => $age >= 36 && $age <= 50)->count()],
            ['label' => '51-65 years', 'count' => $ages->filter(fn (int $age) => $age >= 51 && $age <= 65)->count()],
            ['label' => '65+ years', 'count' => $ages->filter(fn (int $age) => $age > 65)->count()],
        ];

        $genderRows = Patient::query()
            ->whereNotNull('gender')
            ->pluck('gender')
            ->map(fn (string $gender) => Str::lower(trim($gender)));

        $genderTotal = $genderRows->count();
        $male = 0;
        $female = 0;
        $other = 0;

        foreach ($genderRows as $gender) {
            if (in_array($gender, ['male', 'm'], true)) {
                $male++;
                continue;
            }

            if (in_array($gender, ['female', 'f'], true)) {
                $female++;
                continue;
            }

            $other++;
        }

        $genderDistribution = [
            ['label' => 'Male', 'percent' => $genderTotal > 0 ? round(($male / $genderTotal) * 100, 1) : 0],
            ['label' => 'Female', 'percent' => $genderTotal > 0 ? round(($female / $genderTotal) * 100, 1) : 0],
            ['label' => 'Other', 'percent' => $genderTotal > 0 ? round(($other / $genderTotal) * 100, 1) : 0],
        ];

        return [
            'ageGroups' => $ageGroups,
            'newPatients' => Patient::whereBetween('created_at', [$startDate, $endDate])->count(),
            'returningPatients' => Appointment::query()
                ->whereBetween('scheduled_at', [$startDate, $endDate])
                ->distinct('patient_id')
                ->count('patient_id'),
            'genderDistribution' => $genderDistribution,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildAppointmentAnalytics(CarbonInterface $startDate, CarbonInterface $endDate, int $days): array
    {
        $appointments = Appointment::query()
            ->whereBetween('scheduled_at', [$startDate, $endDate])
            ->get(['type', 'status']);

        $typeRows = $appointments
            ->groupBy('type')
            ->map(fn (Collection $items, string $type) => ['type' => $type, 'count' => $items->count()])
            ->sortByDesc('count')
            ->values();

        $total = $appointments->count();
        $confirmed = $appointments->where('status', 'confirmed')->count();
        $pending = $appointments->where('status', 'pending')->count();
        $cancelled = $appointments->where('status', 'cancelled')->count();

        return [
            'types' => $typeRows,
            'statusCards' => [
                'confirmed' => [
                    'count' => $confirmed,
                    'percent' => $total > 0 ? round(($confirmed / $total) * 100, 1) : 0,
                ],
                'pending' => [
                    'count' => $pending,
                    'percent' => $total > 0 ? round(($pending / $total) * 100, 1) : 0,
                ],
                'cancelled' => [
                    'count' => $cancelled,
                    'percent' => $total > 0 ? round(($cancelled / $total) * 100, 1) : 0,
                ],
            ],
            'dailyAverage' => $days > 0 ? ($total / $days) : 0,
        ];
    }
}
