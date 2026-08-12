<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppService;
use App\Models\Tenant;
use App\Models\TenantChannel;
use App\Models\Contact;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\ChatbotKnowledge;
use App\Services\GeminiService;
use App\Services\WhatsAppMediaService;

class WhatsAppWebhookController extends Controller
{
    protected WhatsAppService $whatsapp;
    protected GeminiService $gemini;
    protected WhatsAppMediaService $mediaService;

    public function __construct(WhatsAppService $whatsapp, GeminiService $gemini, WhatsAppMediaService $mediaService)
    {
        $this->whatsapp = $whatsapp;
        $this->gemini = $gemini;
        $this->mediaService = $mediaService;
    }

    /**
     * Verificación del Webhook (GET) — Meta envía esto al configurar el webhook
     */
    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode && $token) {
            if ($mode === 'subscribe' && $token === config('services.whatsapp.verify_token')) {
                Log::info('WHATSAPP_WEBHOOK_VERIFIED');
                return response($challenge, 200)->header('Content-Type', 'text/plain');
            }

            Log::warning('WHATSAPP_WEBHOOK_VERIFICATION_FAILED', [
                'mode' => $mode,
                'token_received' => $token,
            ]);
            return response('Forbidden', 403);
        }

        return response('Bad Request', 400);
    }

    /**
     * Recepción de mensajes y eventos (POST) — Meta envía cada mensaje aquí
     */
    public function handle(Request $request)
    {
        $body = $request->all();

        Log::debug('WHATSAPP_WEBHOOK_RECEIVED', ['body' => $body]);

        // Verificar que el objeto sea de WhatsApp Business Account
        if (!isset($body['object']) || $body['object'] !== 'whatsapp_business_account') {
            return response('Not Found', 404);
        }

        // Procesar cada entrada del webhook
        foreach ($body['entry'] ?? [] as $entry) {
            foreach ($entry['changes'] ?? [] as $change) {
                $value = $change['value'] ?? [];
                $field = $change['field'] ?? '';

                // Solo procesamos eventos del campo "messages"
                if ($field !== 'messages') {
                    continue;
                }

                $metadata = $value['metadata'] ?? [];
                $phoneNumberId = $metadata['phone_number_id'] ?? null;

                // Procesar mensajes entrantes
                if (isset($value['messages'])) {
                    foreach ($value['messages'] as $message) {
                        $this->processIncomingMessage($message, $value, $phoneNumberId);
                    }
                }

                // Procesar actualizaciones de estado (enviado, entregado, leído)
                if (isset($value['statuses'])) {
                    foreach ($value['statuses'] as $status) {
                        $this->processStatusUpdate($status);
                    }
                }
            }
        }

        // Siempre devolver 200 OK para que Meta no reenvíe el webhook
        return response('EVENT_RECEIVED', 200);
    }

    /**
     * Procesar un mensaje entrante de un cliente
     */
    protected function processIncomingMessage(array $message, array $value, ?string $phoneNumberId)
    {
        $from = $message['from'] ?? null;           // Número del cliente (ej: 51988262351)
        $messageId = $message['id'] ?? null;        // ID del mensaje en WhatsApp
        $timestamp = $message['timestamp'] ?? null; // Timestamp del mensaje
        $type = $message['type'] ?? 'unknown';      // Tipo: text, image, audio, etc.

        // Obtener info del contacto (nombre)
        $contacts = $value['contacts'] ?? [];
        $contactName = $contacts[0]['profile']['name'] ?? 'Sin nombre';

        // Extraer el contenido según el tipo de mensaje
        $content = $this->extractMessageContent($message, $type);

        Log::info('WHATSAPP_NEW_MESSAGE', [
            'from' => $from,
            'name' => $contactName,
            'type' => $type,
            'content' => $content,
            'message_id' => $messageId,
            'phone_number_id' => $phoneNumberId,
        ]);

        // Marcar como leído automáticamente
        if ($messageId) {
            $this->whatsapp->markAsRead($messageId);
        }

        // ═══════════════════════════════════════════════════════
        // RESOLVER TENANT DINÁMICAMENTE
        // ═══════════════════════════════════════════════════════
        $tenantChannel = TenantChannel::where('phone_number_id', $phoneNumberId)
            ->where('channel', 'whatsapp')
            ->where('is_active', true)
            ->first();

        $tenantId = $tenantChannel->tenant_id ?? 1; // Fallback a 1 si no se encuentra
        
        // Descargar media si aplica
        $mediaUrl = null;
        $mediaMimeType = null;
        $mediaFileSize = null;

        if (in_array($type, ['image', 'audio', 'document', 'video', 'sticker'])) {
            $mediaObject = $message[$type] ?? [];
            $mediaId = $mediaObject['id'] ?? null;
            $mediaMimeType = $mediaObject['mime_type'] ?? null;
            
            // Meta a veces no envía el tamaño en todos los tipos
            $mediaFileSize = $mediaObject['file_size'] ?? null;

            if ($mediaId && $tenantChannel && $tenantChannel->access_token) {
                // Descargar archivo a Storage
                $mediaUrl = $this->mediaService->downloadAndStoreMedia($mediaId, $tenantChannel->access_token, $mediaMimeType ?? 'application/octet-stream');
            }
        }

        // 1. Buscar o crear el contacto en la tabla `contacts`
        $contact = Contact::firstOrCreate(
            ['tenant_id' => $tenantId, 'phone_number' => $from],
            ['name' => $contactName, 'first_interaction_at' => now()]
        );

        // 2. Buscar o crear la conversación en la tabla `conversations`
        $conversation = Conversation::firstOrCreate(
            ['tenant_id' => $tenantId, 'contact_id' => $contact->id, 'channel' => 'whatsapp'],
            [
                'status' => 'bot_active',
                'priority' => 'normal',
                'auto_assigned' => false,
                'message_count' => 0,
                'unread_count' => 0
            ]
        );

        // 3. Guardar el mensaje en la tabla `messages`
        $inboundMessage = Message::create([
            'tenant_id' => $tenantId,
            'conversation_id' => $conversation->id,
            'contact_id' => $contact->id,
            'channel' => 'whatsapp',
            'direction' => 'inbound',
            'message_type' => $type,
            'content' => $content,
            'media_url' => $mediaUrl,
            'media_mime_type' => $mediaMimeType,
            'media_file_size' => $mediaFileSize,
            'external_message_id' => $messageId,
            'status' => 'delivered'
        ]);

        // Actualizar la conversación (usando DB::raw para evitar race conditions)
        $conversation->update([
            'last_message_at' => now(),
            'last_message_preview' => substr($content, 0, 50),
            'unread_count' => DB::raw('unread_count + 1'),
            'message_count' => DB::raw('message_count + 1'),
        ]);
        $conversation->refresh(); // Recargar para tener valores actualizados

        // === WEBSOCKETS (FASE 5) ===
        broadcast(new \App\Events\MessageReceived($inboundMessage))->toOthers();
        broadcast(new \App\Events\ConversationUpdated($conversation))->toOthers();

        // === RESPUESTA AUTOMÁTICA DEL BOT ===
        // Solo despacha el trabajo (Job) si el mensaje es de texto y el bot está activo
        if ($conversation->status === 'bot_active' && $from && $type === 'text') {
            
            $settings = ChatbotKnowledge::where('tenant_id', $tenantId)->first();
            if (!$settings) $settings = ChatbotKnowledge::first();

            if ($settings && $settings->is_bot_active) {
                // Despachar a la Cola de Trabajos para procesamiento asíncrono
                \App\Jobs\ProcessWhatsAppMessage::dispatch($inboundMessage->id, $tenantId);
            }
        }
    }

    /**
     * Extraer el contenido del mensaje según su tipo
     */
    protected function extractMessageContent(array $message, string $type): ?string
    {
        return match ($type) {
            'text' => $message['text']['body'] ?? null,
            'image' => $message['image']['caption'] ?? '[Imagen]',
            'video' => $message['video']['caption'] ?? '[Video]',
            'audio' => '[Audio]',
            'document' => $message['document']['filename'] ?? '[Documento]',
            'sticker' => '[Sticker]',
            'location' => sprintf(
                '[Ubicación: %s, %s]',
                $message['location']['latitude'] ?? '?',
                $message['location']['longitude'] ?? '?'
            ),
            'contacts' => '[Contacto compartido]',
            'reaction' => $message['reaction']['emoji'] ?? '[Reacción]',
            'interactive' => $message['interactive']['button_reply']['title']
                ?? $message['interactive']['list_reply']['title']
                ?? '[Interactivo]',
            'button' => $message['button']['text'] ?? '[Botón]',
            default => "[Tipo no soportado: {$type}]",
        };
    }

    /**
     * Procesar actualizaciones de estado de mensajes enviados
     * (sent → delivered → read)
     */
    protected function processStatusUpdate(array $status)
    {
        $messageId = $status['id'] ?? null;
        $statusValue = $status['status'] ?? null;      // sent, delivered, read, failed
        $recipientId = $status['recipient_id'] ?? null;
        $timestamp = $status['timestamp'] ?? null;

        Log::info('WHATSAPP_STATUS_UPDATE', [
            'message_id' => $messageId,
            'status' => $statusValue,
            'recipient' => $recipientId,
        ]);

        // ═══════════════════════════════════════════════════════
        // AQUÍ: Actualizar el estado del mensaje en tu BD
        if ($messageId) {
            $msg = Message::where('external_message_id', $messageId)->first();
            if ($msg) {
                $msg->update(['status' => $statusValue]);
                broadcast(new \App\Events\MessageStatusUpdated($msg))->toOthers();
            }
        }
        // ═══════════════════════════════════════════════════════

        // Si el mensaje falló, registrar el error
        if ($statusValue === 'failed') {
            $errors = $status['errors'] ?? [];
            Log::error('WHATSAPP_MESSAGE_FAILED', [
                'message_id' => $messageId,
                'errors' => $errors,
            ]);
        }
    }
}
