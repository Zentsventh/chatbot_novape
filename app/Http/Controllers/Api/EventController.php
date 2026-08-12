<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $events = Appointment::where('tenant_id', $tenantId)
            ->orderBy('scheduled_at', 'asc')
            ->get()
            ->map(function ($appointment) {
                $date = Carbon::parse($appointment->scheduled_at);
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->title,
                    'time' => $date->format('g:ia'),
                    'date' => $date->day,
                    'month' => $date->month,
                    'year' => $date->year,
                    'status' => $appointment->status,
                    'colorClass' => match ($appointment->status) {
                        'confirmed' => 'bg-[#D1FAE5] text-[#047857]',
                        'cancelled' => 'bg-[#FEE2E2] text-[#991B1B]',
                        'completed' => 'bg-[#E0E7FF] text-[#3730A3]',
                        default => 'bg-[#D1FAE5] text-[#047857]',
                    },
                ];
            });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'year' => 'required|integer|min:2020|max:2099',
            'month' => 'required|integer|min:1|max:12',
            'date' => 'required|integer|min:1|max:31',
            'hour' => 'nullable|integer|min:0|max:23',
            'minute' => 'nullable|integer|min:0|max:59',
            'contact_id' => 'nullable|integer|exists:contacts,id',
            'duration_minutes' => 'nullable|integer|min:5|max:480',
        ]);

        $scheduledAt = Carbon::create(
            $validated['year'],
            $validated['month'],
            $validated['date'],
            $validated['hour'] ?? 9,
            $validated['minute'] ?? 0
        );

        $appointment = Appointment::create([
            'tenant_id' => $request->user()->tenant_id,
            'contact_id' => $validated['contact_id'] ?? null,
            'title' => $validated['title'],
            'scheduled_at' => $scheduledAt->format('Y-m-d H:i:s'),
            'duration_minutes' => $validated['duration_minutes'] ?? 60,
            'status' => 'scheduled',
        ]);

        return response()->json(['success' => true, 'appointment' => $appointment], 201);
    }

    public function show(Request $request, Appointment $event)
    {
        if ($event->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($event);
    }

    public function update(Request $request, Appointment $event)
    {
        if ($event->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:200',
            'status' => 'sometimes|string|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
        ]);

        $event->update($validated);
        return response()->json(['success' => true, 'appointment' => $event->fresh()]);
    }

    public function destroy(Request $request, Appointment $event)
    {
        if ($event->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $event->delete();
        return response()->json(['success' => true]);
    }
}
