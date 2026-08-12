<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Tenant;
use App\Models\Contact;
use App\Models\Conversation;
use App\Models\Message;

class ProcessInstagramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $event;

    public function __construct(array $event)
    {
        $this->event = $event;
    }

    public function handle(): void
    {
        try {
            $senderId = $this->event['sender']['id'] ?? null;
            $text = $this->event['message']['text'] ?? null;
            
            if (!$senderId || !$text) {
                return;
            }

            $tenant = Tenant::first();
            if (!$tenant) return;

            // 1. Buscar o crear el contacto
            $contact = Contact::firstOrCreate(
                ['phone_number' => 'ig_' . $senderId, 'tenant_id' => $tenant->id],
                [
                    'name' => 'Usuario de Instagram',
                    'metadata' => json_encode(['channel' => 'instagram', 'ig_sid' => $senderId]),
                    'first_interaction_at' => now(),
                ]
            );

            // 2. Buscar o crear la conversación
            $conversation = Conversation::firstOrCreate(
                ['contact_id' => $contact->id, 'channel' => 'instagram', 'tenant_id' => $tenant->id],
                [
                    'status' => 'active',
                    'is_bot_active' => true,
                    'last_message_at' => now(),
                ]
            );

            // 3. Guardar el mensaje entrante
            $incomingMessage = Message::create([
                'tenant_id' => $tenant->id,
                'conversation_id' => $conversation->id,
                'sender_type' => 'contact',
                'direction' => 'inbound',
                'content' => $text,
                'message_type' => 'text',
                'status' => 'delivered',
            ]);

            $conversation->update(['last_message_at' => now()]);

            // 4. Lógica de bot
            if ($conversation->is_bot_active) {
                Message::create([
                    'tenant_id' => $tenant->id,
                    'conversation_id' => $conversation->id,
                    'sender_type' => 'bot',
                    'direction' => 'outbound',
                    'content' => 'Mensaje automático de IG: ' . $text,
                    'message_type' => 'text',
                    'status' => 'sent',
                ]);
                $conversation->update(['last_message_at' => now()]);
            }
        } catch (\Exception $e) {
            Log::error('Error ProcessInstagramMessage: ' . $e->getMessage());
        }
    }
}
