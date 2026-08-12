<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $contacts = Contact::with('tags')
            ->where('tenant_id', $tenantId)
            ->orderBy('updated_at', 'desc')
            ->paginate(50)
            ->through(function ($contact) {
                $metadata = is_string($contact->metadata) ? json_decode($contact->metadata, true) : ($contact->metadata ?? []);
                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'company' => $contact->company,
                    'phone' => $contact->phone_number,
                    'email' => $contact->email,
                    'initials' => strtoupper(substr($contact->name ?? '??', 0, 2)),
                    'bg' => 'bg-[#8B5CF6]',
                    'channel' => $metadata['channel'] ?? 'whatsapp',
                    'tags' => $contact->tags->pluck('name')->toArray(),
                    'lastActive' => $contact->updated_at->diffForHumans()
                ];
            });

        return response()->json($contacts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:150',
            'channel' => 'nullable|string|in:whatsapp,messenger,instagram',
        ]);

        $contact = Contact::create([
            'tenant_id' => $request->user()->tenant_id,
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone_number' => $validated['phone'] ?? null,
            'company' => $validated['company'] ?? null,
            'metadata' => json_encode(['channel' => $validated['channel'] ?? 'whatsapp']),
            'first_interaction_at' => now(),
        ]);

        return response()->json(['success' => true, 'contact' => $contact], 201);
    }

    public function show(Request $request, Contact $contact)
    {
        if ($contact->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($contact);
    }

    public function update(Request $request, Contact $contact)
    {
        if ($contact->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:150',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:150',
        ]);

        $contact->update(array_filter([
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone_number' => $validated['phone'] ?? null,
            'company' => $validated['company'] ?? null,
        ]));

        return response()->json(['success' => true, 'contact' => $contact->fresh()]);
    }

    public function destroy(Request $request, Contact $contact)
    {
        if ($contact->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $contact->delete();
        return response()->json(['success' => true]);
    }
}
