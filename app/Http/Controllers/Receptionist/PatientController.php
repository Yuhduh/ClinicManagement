<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
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
        return app(DashboardController::class)->receptionist($request, 'patients');
    }

    public function store(StorePatientRequest $request): RedirectResponse
    {
        Patient::create($request->validated());

        return back()->with('status', 'Patient registered successfully.');
    }

    public function update(UpdatePatientRequest $request, Patient $patient): RedirectResponse
    {
        $patient->update($request->validated());

        return back()->with('status', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        $patient->delete();

        return back()->with('status', 'Patient deleted successfully.');
    }
}
