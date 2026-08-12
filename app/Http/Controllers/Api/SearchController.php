<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Conversation;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        $query = $request->query('q');

        if (!$query || strlen($query) < 2) {
            return response()->json(['contacts' => [], 'conversations' => []]);
        }

        $contacts = Contact::where('tenant_id', $tenantId)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('phone_number', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('company', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get();

        $conversations = Conversation::where('tenant_id', $tenantId)
            ->whereHas('contact', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->with('contact')
            ->limit(5)
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'contact_name' => $c->contact->name,
                    'channel' => $c->channel,
                ];
            });

        return response()->json([
            'contacts' => $contacts,
            'conversations' => $conversations,
        ]);
    }
}
