<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = \App\Models\Appointment::all()->map(function($appointment) {
            $date = \Carbon\Carbon::parse($appointment->scheduled_at);
            return [
                'id' => $appointment->id,
                'title' => $appointment->title,
                'time' => $date->format('g:ia'),
                'date' => $date->day,
                'month' => $date->month,
                'colorClass' => 'bg-[#D1FAE5] text-[#047857]' // Simulado
            ];
        });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $date = \Carbon\Carbon::create($request->year, $request->month, $request->date);
        
        $appointment = \App\Models\Appointment::create([
            'tenant_id' => 1,
            'contact_id' => 1, // Fallback si no hay contacto seleccionado
            'title' => $request->title,
            'scheduled_at' => $date->format('Y-m-d H:i:s'),
            'duration_minutes' => 60,
            'status' => 'scheduled'
        ]);

        return response()->json(['success' => true, 'appointment' => $appointment]);
    }
}
