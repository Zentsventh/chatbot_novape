<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\ChatbotKnowledge;
use App\Models\KnowledgeBase;
use App\Services\WhatsAppService;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Log;

class ProcessWhatsAppMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $messageId;
    public $tenantId;

    /**
     * Create a new job instance.
     */
    public function __construct($messageId, $tenantId)
    {
        $this->messageId = $messageId;
        $this->tenantId = $tenantId;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsapp, GeminiService $gemini): void
    {
        // 1. Cargar el mensaje entrante y la conversación
        $incomingMessage = Message::find($this->messageId);
        if (!$incomingMessage) return;

        $conversation = Conversation::find($incomingMessage->conversation_id);
        if (!$conversation) return;

        // 2. Solo procede si el bot está activo para esta conversación
        if ($conversation->status !== 'bot_active') {
            return;
        }

        try {
            $settings = ChatbotKnowledge::where('tenant_id', $this->tenantId)->first();
            if (!$settings) $settings = ChatbotKnowledge::first();

            if ($settings && $settings->is_bot_active) {
                // Recuperar historial
                $historyMsgs = Message::where('conversation_id', $conversation->id)
                    ->where('id', '!=', $incomingMessage->id) // excluir el mensaje actual
                    ->orderBy('created_at', 'desc')
                    ->take(15)
                    ->get()
                    ->reverse();
                
                $history = [];
                foreach ($historyMsgs as $msg) {
                    $history[] = [
                        'is_ai' => ($msg->direction === 'outbound'), 
                        'content' => $msg->content
                    ];
                }

                $systemPrompt = $settings->system_prompt ?? 'Eres un asistente útil y amable de la empresa Novape.';
                
                // === RAG: BÚSQUEDA EN BASE DE DATOS VECTORIAL ===
                $userEmbedding = $gemini->embedText($incomingMessage->content, $this->tenantId);
                
                if ($userEmbedding) {
                    $chunks = \App\Models\KnowledgeChunk::whereHas('knowledgeBase', function($q) {
                        $q->where('tenant_id', $this->tenantId);
                    })->get();

                    $scoredChunks = [];
                    foreach ($chunks as $chunk) {
                        $score = \App\Models\KnowledgeChunk::cosineSimilarity($userEmbedding, $chunk->embedding);
                        if ($score > 0.45) { // Umbral de relevancia
                            $scoredChunks[] = [
                                'score' => $score,
                                'content' => $chunk->content,
                                'name' => $chunk->knowledgeBase->name
                            ];
                        }
                    }

                    // Ordenar de mayor a menor similitud
                    usort($scoredChunks, fn($a, $b) => $b['score'] <=> $a['score']);
                    
                    // Tomar los 3 más relevantes
                    $topChunks = array_slice($scoredChunks, 0, 3);

                    if (count($topChunks) > 0) {
                        $systemPrompt .= "\n\n=== BASE DE CONOCIMIENTOS RELEVANTE ===\nUtiliza estrictamente esta información para responder si aplica a la pregunta:\n";
                        foreach ($topChunks as $tc) {
                            $systemPrompt .= "--- Documento: {$tc['name']} ---\n{$tc['content']}\n\n";
                        }
                    }
                }

                $temperature = $settings->ai_temperature ?? 0.7;

                // Llamar a la API de Gemini
                $aiResponseData = $gemini->generateResponse($systemPrompt, $history, $incomingMessage->content, $temperature, $this->tenantId);

                $contact = $conversation->contact;
                $from = $contact->phone_number;

                // Si es un error de conexión (string)
                if (is_string($aiResponseData)) {
                    $whatsapp->sendTextMessage($from, $aiResponseData);
                    return;
                }

                // Si es un llamado a función (Herramienta/Agente)
                if ($aiResponseData['type'] === 'functionCall') {
                    $funcName = $aiResponseData['data']['name'];
                    $args = $aiResponseData['data']['args'] ?? [];

                    if ($funcName === 'transferir_a_humano') {
                        // === LÓGICA DE HANDOFF ===
                        // 1. Pausar la IA
                        $conversation->update([
                            'status' => 'open', // Estado 'open' significa que está esperando agente humano
                            'priority' => 'high' // Marcar como prioridad alta por la frustración
                        ]);

                        // 2. Avisar al cliente
                        $handoffMsg = "Entiendo perfectamente. Te estoy transfiriendo ahora mismo con uno de nuestros asesores humanos. En breve se comunicarán contigo.";
                        $whatsapp->sendTextMessage($from, $handoffMsg);

                        Message::create([
                            'tenant_id' => $this->tenantId,
                            'conversation_id' => $conversation->id,
                            'contact_id' => $contact->id,
                            'channel' => 'whatsapp',
                            'direction' => 'outbound',
                            'message_type' => 'text',
                            'content' => $handoffMsg,
                            'status' => 'sent',
                            'is_ai_generated' => true,
                        ]);

                        // En Fase 5, aquí iría el evento WebSocket para alertar al equipo.
                        return;
                    }
                }

                // Si es una respuesta de texto normal
                if ($aiResponseData['type'] === 'text') {
                    $aiResponse = $aiResponseData['data'];
                    $response = $whatsapp->sendTextMessage($from, $aiResponse);
                    
                    if(isset($response['data']['messages'][0]['id'])) {
                        $sentMessage = Message::create([
                            'tenant_id' => $this->tenantId,
                            'conversation_id' => $conversation->id,
                            'contact_id' => $contact->id,
                            'channel' => 'whatsapp',
                            'direction' => 'outbound',
                            'message_type' => 'text',
                            'content' => $aiResponse,
                            'external_message_id' => $response['data']['messages'][0]['id'],
                            'status' => 'sent',
                            'is_ai_generated' => true,
                        ]);
                        
                        $conversation->update([
                            'last_message_at' => now(),
                            'last_message_preview' => substr($aiResponse, 0, 50),
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Job ProcessWhatsAppMessage Error', ['message' => $e->getMessage()]);
        }
    }
}
