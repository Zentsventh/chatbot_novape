<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $token;
    protected string $phoneNumberId;
    protected string $apiVersion;
    protected string $baseUrl;

    public function __construct()
    {
        $this->token = config('services.whatsapp.token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->apiVersion = config('services.whatsapp.api_version', 'v21.0');
        $this->baseUrl = "https://graph.facebook.com/{$this->apiVersion}";
    }

    /**
     * Enviar un mensaje de texto simple
     */
    public function sendTextMessage(string $to, string $message, bool $previewUrl = false): array
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'text',
            'text' => [
                'preview_url' => $previewUrl,
                'body' => $message,
            ],
        ];

        return $this->sendRequest($payload);
    }

    /**
     * Enviar una imagen
     */
    public function sendImageMessage(string $to, string $imageUrl, ?string $caption = null): array
    {
        $image = ['link' => $imageUrl];
        if ($caption) {
            $image['caption'] = $caption;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'image',
            'image' => $image,
        ];

        return $this->sendRequest($payload);
    }

    /**
     * Enviar un documento
     */
    public function sendDocumentMessage(string $to, string $documentUrl, ?string $caption = null, ?string $filename = null): array
    {
        $document = ['link' => $documentUrl];
        if ($caption) {
            $document['caption'] = $caption;
        }
        if ($filename) {
            $document['filename'] = $filename;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'document',
            'document' => $document,
        ];

        return $this->sendRequest($payload);
    }

    /**
     * Enviar un mensaje de plantilla (template)
     */
    public function sendTemplateMessage(string $to, string $templateName, string $languageCode = 'es', array $components = []): array
    {
        $template = [
            'name' => $templateName,
            'language' => [
                'code' => $languageCode,
            ],
        ];

        if (!empty($components)) {
            $template['components'] = $components;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'template',
            'template' => $template,
        ];

        return $this->sendRequest($payload);
    }

    /**
     * Marcar un mensaje como leído
     */
    public function markAsRead(string $messageId): array
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'status' => 'read',
            'message_id' => $messageId,
        ];

        return $this->sendRequest($payload);
    }

    /**
     * Descargar un archivo multimedia de WhatsApp
     */
    public function getMediaUrl(string $mediaId): ?string
    {
        try {
            $response = Http::withToken($this->token)
                ->get("{$this->baseUrl}/{$mediaId}");

            if ($response->successful()) {
                return $response->json('url');
            }

            Log::error('WHATSAPP_MEDIA_ERROR', [
                'media_id' => $mediaId,
                'response' => $response->json(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('WHATSAPP_MEDIA_EXCEPTION', [
                'media_id' => $mediaId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Enviar la petición HTTP a la API de WhatsApp
     */
    protected function sendRequest(array $payload): array
    {
        $url = "{$this->baseUrl}/{$this->phoneNumberId}/messages";

        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->post($url, $payload);

            $data = $response->json();

            if ($response->successful()) {
                Log::info('WHATSAPP_MESSAGE_SENT', [
                    'to' => $payload['to'] ?? 'unknown',
                    'type' => $payload['type'] ?? 'unknown',
                    'message_id' => $data['messages'][0]['id'] ?? null,
                ]);
            } else {
                Log::error('WHATSAPP_API_ERROR', [
                    'status' => $response->status(),
                    'response' => $data,
                    'payload_type' => $payload['type'] ?? 'unknown',
                ]);
            }

            return [
                'success' => $response->successful(),
                'data' => $data,
                'status_code' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('WHATSAPP_EXCEPTION', [
                'error' => $e->getMessage(),
                'payload_type' => $payload['type'] ?? 'unknown',
            ]);

            return [
                'success' => false,
                'data' => ['error' => $e->getMessage()],
                'status_code' => 500,
            ];
        }
    }
}
