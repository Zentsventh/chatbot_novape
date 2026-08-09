<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        // En un SaaS real, se filtra por auth()->user()->tenant_id
        $contacts = \App\Models\Contact::all()->map(function($contact) {
            $metadata = json_decode($contact->metadata, true) ?? [];
            return [
                'id' => $contact->id,
                'name' => $contact->name,
                'company' => $contact->company,
                'phone' => $contact->phone_number,
                'email' => $contact->email,
                'initials' => substr($contact->name, 0, 2),
                'bg' => 'bg-[#8B5CF6]', // Color aleatorio o predefinido
                'channel' => $metadata['channel'] ?? 'whatsapp',
                'tags' => ['VIP', 'Cliente Activo'], // Simulado por ahora, hasta conectar Tag
                'lastActive' => $contact->updated_at->diffForHumans()
            ];
        });

        return response()->json($contacts);
    }

    public function store(Request $request)
    {
        $contact = \App\Models\Contact::create([
            'tenant_id' => 1, // Simulado, en real usar auth()->user()->tenant_id
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'company' => $request->company,
            'metadata' => json_encode(['channel' => $request->channel])
        ]);

        return response()->json(['success' => true, 'contact' => $contact]);
    }
}
