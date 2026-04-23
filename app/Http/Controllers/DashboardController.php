<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $baseStats = [
            'today_appointments' => Appointment::whereDate('scheduled_at', now()->toDateString())->count(),
            'pending_prescriptions' => Prescription::count(),
            'new_patients_today' => Patient::whereDate('created_at', now()->toDateString())->count(),
        ];

        if ($user->role === 'admin') {
            $roleStats = [
                'total_staff' => User::whereIn('role', ['doctor', 'receptionist'])->count(),
                'active_staff' => User::whereIn('role', ['doctor', 'receptionist'])->where('is_active', true)->count(),
            ];

            return view('dashboard', [
                'stats' => array_merge($baseStats, $roleStats),
                'recentUsers' => User::latest()->take(5)->get(['name', 'email', 'role', 'is_active']),
            ]);
        }

        if ($user->role === 'doctor') {
            $roleStats = [
                'my_patients' => Patient::whereHas('appointments', function ($query) use ($user) {
                    $query->where('doctor_id', $user->id);
                })->count(),
                'today_records' => MedicalRecord::where('doctor_id', $user->id)
                    ->whereDate('visit_date', now()->toDateString())
                    ->count(),
            ];

            return view('dashboard', [
                'stats' => array_merge($baseStats, $roleStats),
                'todayAppointments' => Appointment::with('patient:id,first_name,last_name')
                    ->where('doctor_id', $user->id)
                    ->whereDate('scheduled_at', now()->toDateString())
                    ->orderBy('scheduled_at')
                    ->take(6)
                    ->get(),
                'recentRecords' => MedicalRecord::with('patient:id,first_name,last_name')
                    ->where('doctor_id', $user->id)
                    ->latest('visit_date')
                    ->take(6)
                    ->get(),
            ]);
        }

        $roleStats = [
            'registered_patients' => Patient::count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
        ];

        return view('dashboard', [
            'stats' => array_merge($baseStats, $roleStats),
            'todayQueue' => Appointment::with(['patient:id,first_name,last_name', 'doctor:id,name'])
                ->whereDate('scheduled_at', now()->toDateString())
                ->orderBy('scheduled_at')
                ->take(8)
                ->get(),
        ]);
    }
}
