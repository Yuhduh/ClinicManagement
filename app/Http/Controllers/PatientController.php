<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $selectedPatientId = $request->integer('patient');

        $patients = Patient::query()
            ->when($search !== '', function ($query) use ($search) {
                $query
                    ->where('patient_code', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $selectedPatient = Patient::query()
            ->when($selectedPatientId, fn ($query) => $query->whereKey($selectedPatientId))
            ->when($selectedPatientId === null, fn ($query) => $query->latest())
            ->first();

        if ($selectedPatient === null) {
            $selectedPatient = $patients->first();
        }

        return view('patients.index', compact('patients', 'search', 'selectedPatient'));
    }

    public function create(): View
    {
        return view('patients.create');
    }

    public function store(StorePatientRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['patient_code'] = $this->generatePatientCode();

        Patient::create($data);

        return redirect()->route('patients.index')->with('patientSuccess', 'Patient registered successfully.');
    }

    public function edit(Patient $patient): View
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(UpdatePatientRequest $request, Patient $patient): RedirectResponse
    {
        $patient->update($request->validated());

        return redirect()->route('patients.index')->with('status', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        $patient->delete();

        return redirect()->route('patients.index')->with('status', 'Patient deleted successfully.');
    }

    private function generatePatientCode(): string
    {
        $latestCode = Patient::query()
            ->where('patient_code', 'like', 'P%')
            ->orderByDesc('id')
            ->value('patient_code');

        $nextNumber = 1;

        if (is_string($latestCode) && preg_match('/^P(\d+)$/', $latestCode, $matches) === 1) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        return 'P'.str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
