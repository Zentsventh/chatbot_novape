<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CannedResponse;

class CannedResponseController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        
        $responses = CannedResponse::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        return response()->json($responses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'shortcut' => 'nullable|string|max:50',
            'content' => 'required|string',
            'category' => 'nullable|string|max:50',
        ]);

        $response = CannedResponse::create([
            'tenant_id' => $request->user()->tenant_id,
            'title' => $validated['title'],
            'shortcut' => $validated['shortcut'] ?? null,
            'content' => $validated['content'],
            'category' => $validated['category'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        return response()->json(['success' => true, 'data' => $response], 201);
    }

    public function update(Request $request, CannedResponse $cannedResponse)
    {
        if ($cannedResponse->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:100',
            'shortcut' => 'nullable|string|max:50',
            'content' => 'sometimes|string',
            'category' => 'nullable|string|max:50',
            'is_active' => 'sometimes|boolean',
        ]);

        $cannedResponse->update($validated);

        return response()->json(['success' => true, 'data' => $cannedResponse->fresh()]);
    }

    public function destroy(Request $request, CannedResponse $cannedResponse)
    {
        if ($cannedResponse->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $cannedResponse->delete();
        return response()->json(['success' => true]);
    }
}
