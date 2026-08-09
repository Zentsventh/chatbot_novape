<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        $deals = \App\Models\Deal::with('contact')->get()->map(function($deal) {
            return [
                'id' => $deal->id,
                'columnId' => $deal->stage,
                'title' => $deal->title,
                'company' => $deal->contact->company ?? 'Sin empresa',
                'contact' => $deal->contact->name ?? 'Desconocido',
                'initials' => isset($deal->contact->name) ? substr($deal->contact->name, 0, 2) : '??',
                'value' => (int) $deal->value,
                'tags' => ['Oportunidad'], // Simulado
            ];
        });

        return response()->json($deals);
    }

    public function store(Request $request)
    {
        $deal = \App\Models\Deal::create([
            'tenant_id' => 1,
            'contact_id' => 1, // Fallback
            'title' => $request->title,
            'value' => $request->value,
            'stage' => 'lead' // Todas nacen en 'lead'
        ]);

        return response()->json(['success' => true, 'deal' => $deal]);
    }

    public function updateStage(Request $request, $id)
    {
        $deal = \App\Models\Deal::find($id);
        if ($deal) {
            $deal->update(['stage' => $request->stage]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
