<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ApiUsageLog;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    /**
     * Send a prompt to Gemini and get a response.
     *
     * @param string $systemPrompt Instructions on how the AI should behave
     * @param array $history Previous conversation history
     * @param string $newMessage The latest message from the user
     * @param float $temperature The temperature to use (0.0 to 1.0)
     * @return array|string The generated response or function call
     */
    public function generateResponse(string $systemPrompt, array $history, string $newMessage, float $temperature = 0.7, ?int $tenantId = null): array|string
    {
        $contents = [];

        // Add history
        foreach ($history as $msg) {
            $role = $msg['is_ai'] ? 'model' : 'user';
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $msg['content']]]
            ];
        }

        // Add the new message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $newMessage]]
        ];

        $payload = [
            'system_instruction' => [
                'parts' => [
                    'text' => $systemPrompt
                ]
            ],
            'contents' => $contents,
            'tools' => [
                [
                    'functionDeclarations' => [
                        [
                            'name' => 'transferir_a_humano',
                            'description' => 'Transfiere la conversación a un agente humano. Usa esta función SOLAMENTE si el usuario está muy enojado, frustrado, o pide explícitamente hablar con un humano o un asesor.',
                            'parameters' => [
                                'type' => 'OBJECT',
                                'properties' => [
                                    'reason' => [
                                        'type' => 'STRING',
                                        'description' => 'El motivo por el cual se transfiere a un humano (ej: Usuario enojado, Petición explícita).'
                                    ]
                                ],
                                'required' => ['reason']
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => $temperature,
            ]
        ];

        $url = $this->baseUrl . 'gemini-flash-latest:generateContent?key=' . $this->apiKey;

        try {
            $startTime = microtime(true);
            $response = Http::post($url, $payload);
            $durationMs = round((microtime(true) - $startTime) * 1000);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($tenantId && isset($data['usageMetadata'])) {
                    ApiUsageLog::create([
                        'tenant_id' => $tenantId,
                        'service' => 'gemini',
                        'endpoint' => 'generateContent',
                        'tokens_input' => $data['usageMetadata']['promptTokenCount'] ?? 0,
                        'tokens_output' => $data['usageMetadata']['candidatesTokenCount'] ?? 0,
                        'tokens_total' => $data['usageMetadata']['totalTokenCount'] ?? 0,
                        'estimated_cost' => 0, // calculate if needed
                        'response_time_ms' => $durationMs,
                        'status_code' => $response->status(),
                        'is_successful' => true,
                        'created_at' => now(),
                    ]);
                }
                
                // Check if it's a function call
                if (isset($data['candidates'][0]['content']['parts'][0]['functionCall'])) {
                    return [
                        'type' => 'functionCall',
                        'data' => $data['candidates'][0]['content']['parts'][0]['functionCall']
                    ];
                }

                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return [
                        'type' => 'text',
                        'data' => $data['candidates'][0]['content']['parts'][0]['text']
                    ];
                }
            }

            Log::error('Gemini API Error', ['status' => $response->status(), 'body' => $response->body()]);
            return "Lo siento, en este momento estoy teniendo problemas para procesar tu solicitud. Por favor intenta más tarde o espera a que un agente humano te atienda.";
        } catch (\Exception $e) {
            Log::error('Gemini API Exception', ['message' => $e->getMessage()]);
            return "Lo siento, en este momento el sistema inteligente no está disponible.";
        }
    }

    /**
     * Generate embedding vector for a given text.
     *
     * @param string $text
     * @return array|null The embedding vector (array of floats) or null on error
     */
    public function embedText(string $text, ?int $tenantId = null): ?array
    {
        $url = $this->baseUrl . 'text-embedding-004:embedContent?key=' . $this->apiKey;

        $payload = [
            'model' => 'models/text-embedding-004',
            'content' => [
                'parts' => [
                    ['text' => $text]
                ]
            ]
        ];

        try {
            $startTime = microtime(true);
            $response = Http::post($url, $payload);
            $durationMs = round((microtime(true) - $startTime) * 1000);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($tenantId) {
                    ApiUsageLog::create([
                        'tenant_id' => $tenantId,
                        'service' => 'gemini',
                        'endpoint' => 'embedContent',
                        'tokens_total' => count(explode(' ', $text)) * 1.5, // Approx tokens for embedding
                        'response_time_ms' => $durationMs,
                        'status_code' => $response->status(),
                        'is_successful' => true,
                        'created_at' => now(),
                    ]);
                }
                if (isset($data['embedding']['values'])) {
                    return $data['embedding']['values'];
                }
            }

            Log::error('Gemini Embedding Error', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('Gemini Embedding Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }
}
