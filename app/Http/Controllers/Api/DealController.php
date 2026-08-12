<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deal;

class DealController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $deals = Deal::with(['contact', 'tags'])
            ->where('tenant_id', $tenantId)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($deal) {
                return [
                    'id' => $deal->id,
                    'columnId' => $deal->stage,
                    'title' => $deal->title,
                    'company' => $deal->contact->company ?? 'Sin empresa',
                    'contact' => $deal->contact->name ?? 'Desconocido',
                    'initials' => isset($deal->contact->name) ? strtoupper(substr($deal->contact->name, 0, 2)) : '??',
                    'value' => (int) $deal->value,
                    'tags' => $deal->tags->map(function($tag) {
                        return [
                            'name' => $tag->name,
                            'color' => $tag->color ?? '#E2E8F0'
                        ];
                    })->toArray(),
                ];
            });

        return response()->json($deals);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'value' => 'required|numeric|min:0',
            'contact_id' => 'nullable|integer|exists:contacts,id',
            'stage' => 'nullable|string|in:lead,prospect,proposal,negotiation,won,lost',
        ]);

        $deal = Deal::create([
            'tenant_id' => $request->user()->tenant_id,
            'contact_id' => $validated['contact_id'] ?? null,
            'title' => $validated['title'],
            'value' => $validated['value'],
            'stage' => $validated['stage'] ?? 'lead',
        ]);

        return response()->json(['success' => true, 'deal' => $deal], 201);
    }

    public function show(Request $request, Deal $deal)
    {
        if ($deal->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($deal->load('contact'));
    }

    public function update(Request $request, Deal $deal)
    {
        if ($deal->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:200',
            'value' => 'sometimes|numeric|min:0',
            'stage' => 'sometimes|string|in:lead,prospect,proposal,negotiation,won,lost',
        ]);

        $deal->update($validated);
        return response()->json(['success' => true, 'deal' => $deal->fresh()]);
    }

    public function updateStage(Request $request, $id)
    {
        $deal = Deal::where('tenant_id', $request->user()->tenant_id)->findOrFail($id);

        $validated = $request->validate([
            'stage' => 'required|string|in:lead,prospect,proposal,negotiation,won,lost',
        ]);

        $deal->update(['stage' => $validated['stage']]);
        return response()->json(['success' => true]);
    }

    public function destroy(Request $request, Deal $deal)
    {
        if ($deal->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $deal->delete();
        return response()->json(['success' => true]);
    }
}
