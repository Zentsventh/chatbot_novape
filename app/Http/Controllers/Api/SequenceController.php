<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sequence;

class SequenceController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        $sequences = Sequence::where('tenant_id', $tenantId)->with('steps')->get();
        return response()->json($sequences);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'channel' => 'required|in:whatsapp,messenger,instagram,all',
            'trigger_type' => 'required|string',
        ]);

        $sequence = Sequence::create(array_merge($validated, [
            'tenant_id' => $request->user()->tenant_id,
            'created_by' => $request->user()->id,
        ]));

        return response()->json(['success' => true, 'sequence' => $sequence], 201);
    }
}
