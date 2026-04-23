<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVisitRequest;
use App\Http\Requests\UpdateVisitRequest;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VisitController extends Controller
{
    public function index(): View
    {
        $selectedPatientId = request()->query('patient');
        $activeTab = request()->query('tab', 'visits');

        $patients = Patient::orderBy('first_name')->orderBy('last_name')->get();
        $selectedPatient = null;
        $visits = collect();
        $prescriptions = collect();

        if ($selectedPatientId) {
            $selectedPatient = Patient::findOrFail($selectedPatientId);
            $visits = MedicalRecord::where('patient_id', $selectedPatientId)
                ->where('doctor_id', auth()->id())
                ->latest('visit_date')
                ->get();
            $prescriptions = $selectedPatient->prescriptions()
                ->where('doctor_id', auth()->id())
                ->latest()
                ->get();
        } elseif ($patients->count() > 0) {
            $selectedPatient = $patients->first();
            $visits = MedicalRecord::where('patient_id', $selectedPatient->id)
                ->where('doctor_id', auth()->id())
                ->latest('visit_date')
                ->get();
            $prescriptions = $selectedPatient->prescriptions()
                ->where('doctor_id', auth()->id())
                ->latest()
                ->get();
        }

        return view('records.index', compact('patients', 'selectedPatient', 'selectedPatientId', 'visits', 'prescriptions', 'activeTab'));
    }

    public function create(): View
    {
        return view('records.create', [
            'patients' => Patient::orderBy('first_name')->orderBy('last_name')->get(),
        ]);
    }

    public function store(StoreVisitRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['doctor_id'] = $request->user()->id;
        
        // Generate unique visit reference if not provided
        if (empty($data['visit_reference'])) {
            $data['visit_reference'] = 'VR-' . date('YmdHis') . '-' . random_int(1000, 9999);
        }

        $record = MedicalRecord::create($data);

        return redirect()->route('records.index', ['patient' => $record->patient_id])->with('visitSuccess', true);
    }

    public function edit(MedicalRecord $record): View
    {
        abort_if($record->doctor_id !== auth()->id(), 403);

        return view('records.edit', [
            'record' => $record,
            'patients' => Patient::orderBy('first_name')->orderBy('last_name')->get(),
        ]);
    }

    public function update(UpdateVisitRequest $request, MedicalRecord $record): RedirectResponse
    {
        abort_if($record->doctor_id !== auth()->id(), 403);

        $data = $request->validated();
        $data['doctor_id'] = $request->user()->id;

        $record->update($data);

        return redirect()->route('records.index', ['patient' => $record->patient_id])->with('visitSuccess', true);
    }
}
