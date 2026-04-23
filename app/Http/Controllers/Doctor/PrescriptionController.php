<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Requests\StorePrescriptionRequest;
use App\Http\Requests\UpdatePrescriptionRequest;
use App\Models\Prescription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrescriptionController extends Controller
{
    public function index(Request $request): View
    {
        return app(DashboardController::class)->doctor($request, 'prescriptions');
    }

    public function store(StorePrescriptionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['doctor_id'] = $request->user()->id;
        $data['reference'] = $this->generateReference();

        Prescription::create($data);

        return back()->with('status', 'Prescription saved successfully.');
    }

    public function update(UpdatePrescriptionRequest $request, Prescription $prescription): RedirectResponse
    {
        abort_if($prescription->doctor_id !== auth()->id(), 403);

        $data = $request->validated();
        $data['doctor_id'] = $request->user()->id;

        $prescription->update($data);

        return back()->with('status', 'Prescription updated successfully.');
    }

    public function destroy(Prescription $prescription): RedirectResponse
    {
        abort_if($prescription->doctor_id !== auth()->id(), 403);

        $prescription->delete();

        return back()->with('status', 'Prescription deleted successfully.');
    }

    private function generateReference(): string
    {
        do {
            $reference = 'RX'.strtoupper((string) str()->random(8));
        } while (Prescription::where('reference', $reference)->exists());

        return $reference;
    }
}
