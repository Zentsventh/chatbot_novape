<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessMessengerMessage;
use App\Jobs\ProcessInstagramMessage;

class WebhookController extends Controller
{
    /**
     * Token de validación — leído desde config/services.php
     */
    private function getVerifyToken(): string
    {
        return config('services.whatsapp.verify_token', 'smart_ai_token_2026');
    }

    /**
     * Verificación del Webhook (Petición GET desde Meta)
     */
    public function verifyMessenger(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        // Verifica si el modo y el token están presentes
        if ($mode && $token) {
            // Verifica que el modo sea 'subscribe' y el token coincida
            if ($mode === 'subscribe' && $token === $this->getVerifyToken()) {
                Log::info('WEBHOOK_VERIFIED');
                return response($challenge, 200);
            } else {
                Log::warning('WEBHOOK_VERIFICATION_FAILED');
                return response('Forbidden', 403);
            }
        }
        
        return response('Bad Request', 400);
    }

    /**
     * Recepción de mensajes (Petición POST desde Meta)
     */
    public function handleMessenger(Request $request)
    {
        // Parsear el cuerpo de la solicitud JSON
        $body = $request->all();

        // Verificar si es un evento de una página (page)
        if (isset($body['object']) && $body['object'] === 'page') {

            // Iterar sobre cada entrada (puede haber múltiples si hay varios mensajes a la vez)
            foreach ($body['entry'] as $entry) {
                // Obtener el arreglo de mensajes (messaging)
                $webhookEvent = $entry['messaging'][0] ?? null;
                
                if ($webhookEvent) {
                    // Obtener el Sender PSID (quién envía)
                    $senderPsid = $webhookEvent['sender']['id'];
                    
                    // Comprobar si el evento es un mensaje
                    if (isset($webhookEvent['message'])) {
                        Log::info('NUEVO_MENSAJE_MESSENGER', [
                            'sender_id' => $senderPsid,
                            'message_text' => $webhookEvent['message']['text'] ?? 'Adjunto'
                        ]);
                        
                        ProcessMessengerMessage::dispatch($webhookEvent);
                    }
                }
            }

            // Devolver un '200 OK' a todas las peticiones POST para indicar recepción
            return response('EVENT_RECEIVED', 200);
        }

        // Devolver '404 Not Found' si el evento no proviene de una página ('page')
        return response('Not Found', 404);
    }

    /**
     * Verificación del Webhook de Instagram
     */
    public function verifyInstagram(Request $request)
    {
        return $this->verifyMessenger($request); // Usa la misma lógica
    }

    /**
     * Recepción de mensajes de Instagram
     */
    public function handleInstagram(Request $request)
    {
        $body = $request->all();

        if (isset($body['object']) && $body['object'] === 'instagram') {
            foreach ($body['entry'] as $entry) {
                $webhookEvent = $entry['messaging'][0] ?? null;
                
                if ($webhookEvent) {
                    $senderId = $webhookEvent['sender']['id'] ?? null;
                    
                    if (isset($webhookEvent['message'])) {
                        Log::info('NUEVO_MENSAJE_INSTAGRAM', [
                            'sender_id' => $senderId,
                            'message_text' => $webhookEvent['message']['text'] ?? 'Adjunto'
                        ]);
                        
                        ProcessInstagramMessage::dispatch($webhookEvent);
                    }
                }
            }
            return response('EVENT_RECEIVED', 200);
        }
        return response('Not Found', 404);
    }
}
