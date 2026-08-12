<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;
use App\Events\ConversationUpdated;
use App\Events\MessageReceived;

class InboxController extends Controller
{
    /**
     * Obtener la lista de conversaciones del tenant autenticado.
     */
    public function conversations(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $conversations = Conversation::with(['contact', 'assignedUser'])
            ->where('tenant_id', $tenantId)
            ->orderBy('last_message_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(50);

        $conversations->getCollection()->transform(function ($conv) {
            return [
                'id' => $conv->id,
                'contactName' => $conv->contact->name ?? 'Desconocido',
                'initials' => strtoupper(substr($conv->contact->name ?? '??', 0, 2)),
                'avatarColor' => '#0056D2',
                'phone' => $conv->contact->phone_number ?? null,
                'channel' => $conv->channel,
                'lastMessagePreview' => $conv->last_message_preview,
                'lastMessageTime' => $conv->last_message_at ? $conv->last_message_at->format('H:i') : null,
                'unreadCount' => $conv->unread_count,
                'priority' => $conv->priority,
                'isBotActive' => $conv->status === 'bot_active',
                'botName' => 'Aurora',
                'assignedUserId' => $conv->assigned_user_id,
                'agentName' => $conv->assignedUser ? $conv->assignedUser->name : null,
            ];
        });

        return response()->json($conversations);
    }

    /**
     * Obtener los mensajes de una conversación.
     */
    public function messages(Request $request, Conversation $conversation)
    {
        // Verificar acceso por tenant
        $this->authorizeTenant($request, $conversation);

        // Marcar mensajes como leídos
        $conversation->update(['unread_count' => 0]);

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->paginate(100);

        $messages->getCollection()->transform(function ($msg) {
            return [
                'id' => $msg->id,
                'direction' => $msg->direction,
                'messageType' => $msg->message_type,
                'content' => $msg->content,
                'mediaUrl' => $msg->media_url,
                'mediaMimeType' => $msg->media_mime_type,
                'time' => $msg->created_at->format('H:i'),
                'status' => $msg->status,
                'isInternalNote' => (bool) $msg->is_internal_note,
                'isSystemEvent' => false,
            ];
        });

        return response()->json($messages);
    }

    /**
     * Enviar un nuevo mensaje desde la bandeja.
     */
    public function sendMessage(Request $request, Conversation $conversation, WhatsAppService $whatsapp)
    {
        $this->authorizeTenant($request, $conversation);

        $request->validate([
            'content' => 'required|string|max:4096',
        ]);

        $content = $request->input('content');
        $tenantId = $request->user()->tenant_id;

        // 1. Guardar en BD
        $message = Message::create([
            'tenant_id' => $tenantId,
            'conversation_id' => $conversation->id,
            'contact_id' => $conversation->contact_id,
            'user_id' => $request->user()->id,
            'channel' => $conversation->channel,
            'direction' => 'outbound',
            'message_type' => 'text',
            'content' => $content,
            'status' => 'queued',
        ]);

        $conversation->update([
            'last_message_preview' => substr($content, 0, 50),
            'last_message_at' => now(),
        ]);

        // 2. Enviar por WhatsApp API
        if ($conversation->channel === 'whatsapp' && $conversation->contact->phone_number) {
            try {
                $response = $whatsapp->sendTextMessage($conversation->contact->phone_number, $content);
                if (isset($response['data']['messages'][0]['id'])) {
                    $message->update([
                        'external_message_id' => $response['data']['messages'][0]['id'],
                        'status' => 'sent'
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('API SEND ERROR', ['msg' => $e->getMessage()]);
                $message->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'direction' => $message->direction,
                'content' => $message->content,
                'time' => $message->created_at->format('H:i'),
                'status' => $message->status,
                'isInternalNote' => false,
                'isSystemEvent' => false,
            ]
        ]);
    }

    /**
     * Asignar conversación a un agente.
     */
    public function assignAgent(Request $request, Conversation $conversation)
    {
        $this->authorizeTenant($request, $conversation);

        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $userId = $request->input('user_id');
        $user = User::findOrFail($userId);

        $conversation->update([
            'assigned_user_id' => $user->id,
            'status' => 'human_active',
            'is_bot_paused' => true,
            'bot_paused_at' => now(),
            'bot_paused_by' => $request->user()->id,
        ]);

        $conversation->load('assignedUser');
        broadcast(new ConversationUpdated($conversation))->toOthers();

        return response()->json([
            'success' => true,
            'assignedUserId' => $userId,
            'agentName' => $conversation->assignedUser->name
        ]);
    }

    /**
     * Desasignar y reactivar bot.
     */
    public function unassignAgent(Request $request, Conversation $conversation)
    {
        $this->authorizeTenant($request, $conversation);

        $conversation->update([
            'assigned_user_id' => null,
            'status' => 'bot_active',
            'is_bot_paused' => false,
            'bot_paused_at' => null,
            'bot_paused_by' => null,
        ]);

        broadcast(new ConversationUpdated($conversation))->toOthers();

        return response()->json(['success' => true]);
    }

    /**
     * Enviar nota interna.
     */
    public function addInternalNote(Request $request, Conversation $conversation)
    {
        $this->authorizeTenant($request, $conversation);

        $request->validate([
            'content' => 'required|string|max:4096',
        ]);

        $userId = $request->user()->id;

        $message = Message::create([
            'tenant_id' => $conversation->tenant_id,
            'conversation_id' => $conversation->id,
            'contact_id' => $conversation->contact_id,
            'user_id' => $userId,
            'channel' => $conversation->channel,
            'direction' => 'outbound',
            'message_type' => 'text',
            'content' => $request->input('content'),
            'is_internal_note' => true,
            'status' => 'sent',
        ]);

        // Process mentions like @Name
        preg_match_all('/@([a-zA-Z0-9_]+)/', $request->input('content'), $matches);
        if (!empty($matches[1])) {
            $mentionedUsers = User::whereIn('name', $matches[1])->get();
            // TODO: Trigger UserMentioned event/notification
        }

        broadcast(new MessageReceived($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'direction' => $message->direction,
                'content' => $message->content,
                'time' => $message->created_at->format('H:i'),
                'status' => $message->status,
                'isInternalNote' => true,
                'isSystemEvent' => false,
            ]
        ]);
    }

    /**
     * Verificar que la conversación pertenece al tenant del usuario autenticado.
     */
    private function authorizeTenant(Request $request, Conversation $conversation): void
    {
        if ($conversation->tenant_id !== $request->user()->tenant_id) {
            abort(403, 'No tienes acceso a esta conversación.');
        }
    }
}
