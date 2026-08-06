<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Token de validación (debe ser el mismo que pongamos en Meta Developer)
     */
    private $verifyToken = 'smart_ai_token_2026';

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
            if ($mode === 'subscribe' && $token === $this->verifyToken) {
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
                    }
                }
            }

            // Devolver un '200 OK' a todas las peticiones POST para indicar recepción
            return response('EVENT_RECEIVED', 200);
        }

        // Devolver '404 Not Found' si el evento no proviene de una página ('page')
        return response('Not Found', 404);
    }
}
