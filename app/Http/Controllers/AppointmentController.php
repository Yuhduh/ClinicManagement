<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $viewMode = $request->query('view', 'list') === 'calendar' ? 'calendar' : 'list';

        $baseQuery = Appointment::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('patient', function ($patientQuery) use ($search) {
                    $patientQuery
                        ->where('patient_code', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->with(['patient:id,patient_code,first_name,last_name', 'doctor:id,name'])
            ->latest('scheduled_at');

        $appointments = (clone $baseQuery)
            ->paginate(12)
            ->withQueryString();

        $calendarAppointments = (clone $baseQuery)
            ->get()
            ->groupBy(fn (Appointment $appointment) => $appointment->scheduled_at->toDateString())
            ->map(function ($items, string $date) {
                return [
                    'date' => Carbon::parse($date),
                    'items' => $items->sortBy('scheduled_at')->values(),
                ];
            })
            ->values();

        return view('appointments.index', compact('appointments', 'calendarAppointments', 'search', 'viewMode'));
    }

    public function create(): View
    {
        return view('appointments.create', [
            'patients' => Patient::orderBy('first_name')->orderBy('last_name')->get(),
            'doctors' => User::where('role', 'doctor')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['scheduled_at'] = Carbon::parse($data['appointment_date'].' '.$data['appointment_time']);
        unset($data['appointment_date'], $data['appointment_time']);

        Appointment::create($data);

        return redirect()->route('appointments.index')->with('status', 'Appointment created successfully.');
    }

    public function edit(Appointment $appointment): View
    {
        return view('appointments.edit', [
            'appointment' => $appointment,
            'patients' => Patient::orderBy('first_name')->orderBy('last_name')->get(),
            'doctors' => User::where('role', 'doctor')->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = $appointment->created_by ?? $request->user()->id;
        $data['scheduled_at'] = Carbon::parse($data['appointment_date'].' '.$data['appointment_time']);
        unset($data['appointment_date'], $data['appointment_time']);

        $appointment->update($data);

        return redirect()->route('appointments.index')->with('status', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return redirect()->route('appointments.index')->with('status', 'Appointment deleted successfully.');
    }
}
