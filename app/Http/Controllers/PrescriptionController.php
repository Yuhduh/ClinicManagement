<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrescriptionRequest;
use App\Http\Requests\UpdatePrescriptionRequest;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PrescriptionController extends Controller
{
    public function index(): View
    {
        $prescriptions = Prescription::with('patient:id,first_name,last_name', 'doctor:id,first_name,last_name,middle_initial')
            ->where('doctor_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('prescriptions.index', compact('prescriptions'));
    }

    public function create(): View
    {
        return view('prescriptions.create', [
            'patients' => Patient::orderBy('first_name')->orderBy('last_name')->get(),
        ]);
    }

    public function store(StorePrescriptionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['doctor_id'] = $request->user()->id;
        $data['reference'] = $this->generateReference();

        $prescription = Prescription::create($data);

        return redirect()->route('prescriptions.index')->with('prescriptionSuccess', true);
    }

    public function edit(Prescription $prescription): View
    {
        abort_if($prescription->doctor_id !== auth()->id(), 403);

        return view('prescriptions.edit', [
            'prescription' => $prescription,
            'patients' => Patient::orderBy('first_name')->orderBy('last_name')->get(),
        ]);
    }

    public function update(UpdatePrescriptionRequest $request, Prescription $prescription): RedirectResponse
    {
        abort_if($prescription->doctor_id !== auth()->id(), 403);

        $data = $request->validated();
        $data['doctor_id'] = $request->user()->id;

        $prescription->update($data);

        return redirect()->route('prescriptions.index')->with('prescriptionSuccess', true);
    }

    public function destroy(Prescription $prescription): RedirectResponse
    {
        abort_if($prescription->doctor_id !== auth()->id(), 403);

        $prescription->delete();

        return redirect()->route('prescriptions.index');
    }

    private function generateReference(): string
    {
        do {
            $reference = 'RX'.strtoupper((string) str()->random(8));
        } while (Prescription::where('reference', $reference)->exists());

        return $reference;
    }
}
