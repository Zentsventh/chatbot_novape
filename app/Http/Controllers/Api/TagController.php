<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Contact;
use App\Models\Deal;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        $tags = Tag::where('tenant_id', $tenantId)->get();
        return response()->json($tags);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'color' => 'nullable|string|max:20',
        ]);

        $tag = Tag::create([
            'tenant_id' => $request->user()->tenant_id,
            'name' => $validated['name'],
            'color' => $validated['color'] ?? '#E2E8F0',
        ]);

        return response()->json(['success' => true, 'tag' => $tag], 201);
    }

    public function destroy(Request $request, Tag $tag)
    {
        if ($tag->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }
        $tag->delete();
        return response()->json(['success' => true]);
    }

    public function attachToContact(Request $request, Contact $contact)
    {
        if ($contact->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }
        $validated = $request->validate(['tag_ids' => 'required|array', 'tag_ids.*' => 'integer|distinct']);
        $tagIds = $validated['tag_ids'];
        if (Tag::where('tenant_id', $request->user()->tenant_id)->whereIn('id', $tagIds)->count() !== count($tagIds)) {
            abort(403);
        }
        $contact->tags()->syncWithoutDetaching($tagIds);
        return response()->json(['success' => true, 'tags' => $contact->tags]);
    }

    public function detachFromContact(Request $request, Contact $contact, Tag $tag)
    {
        if ($contact->tenant_id !== $request->user()->tenant_id || $tag->tenant_id !== $request->user()->tenant_id) abort(403);
        $contact->tags()->detach($tag->id);
        return response()->json(['success' => true]);
    }
}
