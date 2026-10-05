<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeamConversation;
use App\Models\TeamMessage;
use App\Models\User;
use App\Events\TeamMessageSent;

class TeamChatController extends Controller
{
    /**
     * List all team conversations for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get conversations where the user is a participant
        $conversations = TeamConversation::whereHas('participants', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->with(['participants' => function($q) use ($user) {
            // Get the other participants for naming/avatars
            $q->where('user_id', '!=', $user->id);
        }, 'messages' => function($q) {
            $q->latest()->limit(1); // Get last message preview
        }])
        ->get()
        ->map(function ($conv) {
            $otherParticipant = $conv->participants->first();
            
            return [
                'id' => $conv->id,
                'name' => $conv->is_group ? $conv->name : ($otherParticipant->name ?? 'Desconocido'),
                'is_group' => $conv->is_group,
                'avatar' => $conv->is_group ? null : ($otherParticipant->avatar_url ?? null),
                'last_message' => $conv->messages->first()->content ?? null,
                'last_message_time' => $conv->messages->first()?->created_at->format('H:i'),
            ];
        });

        return response()->json($conversations);
    }

    /**
     * Get messages for a specific conversation.
     */
    public function messages(Request $request, TeamConversation $conversation)
    {
        $user = $request->user();

        // Ensure user belongs to the conversation
        if (!$conversation->participants()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $conversation->messages()
            ->with('sender:id,name,avatar_url')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'user_id' => $msg->user_id,
                    'sender_name' => $msg->sender->name ?? 'Desconocido',
                    'sender_avatar' => $msg->sender->avatar_url ?? null,
                    'content' => $msg->content,
                    'time' => $msg->created_at->format('H:i'),
                ];
            });

        return response()->json($messages);
    }

    /**
     * Send a message to a team conversation.
     */
    public function sendMessage(Request $request, TeamConversation $conversation)
    {
        $user = $request->user();

        // Ensure user belongs to the conversation
        if (!$conversation->participants()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:4000',
        ]);

        $message = TeamMessage::create([
            'team_conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'content' => $request->content,
        ]);

        // Broadcast event
        broadcast(new TeamMessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'user_id' => $message->user_id,
                'sender_name' => $user->name,
                'sender_avatar' => $user->avatar_url,
                'content' => $message->content,
                'time' => $message->created_at->format('H:i'),
            ]
        ]);
    }

    /**
     * Start a new 1-on-1 chat or get existing one.
     */
    public function startChat(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $targetUserId = $request->user_id;
        if (!User::where('tenant_id', $user->tenant_id)->whereKey($targetUserId)->exists()) {
            abort(403);
        }

        if ($user->id == $targetUserId) {
            return response()->json(['error' => 'No puedes chatear contigo mismo'], 400);
        }

        // Check if a 1-on-1 conversation already exists between these two users
        $conversation = TeamConversation::where('tenant_id', $user->tenant_id)
            ->where('is_group', false)
            ->whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereHas('participants', function ($q) use ($targetUserId) {
                $q->where('user_id', $targetUserId);
            })
            ->first();

        if (!$conversation) {
            // Create new conversation
            $conversation = TeamConversation::create([
                'tenant_id' => $user->tenant_id,
                'is_group' => false,
            ]);

            $conversation->participants()->attach([$user->id, $targetUserId]);
        }

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id
        ]);
    }

    /**
     * List users in the same tenant to start chats with.
     */
    public function users(Request $request)
    {
        $user = $request->user();

        $users = User::where('tenant_id', $user->tenant_id)
            ->where('id', '!=', $user->id)
            ->get(['id', 'name', 'avatar_url', 'is_online']);

        return response()->json($users);
    }
}
