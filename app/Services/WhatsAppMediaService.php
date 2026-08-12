<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WhatsAppMediaService
{
    /**
     * Descarga un archivo multimedia de los servidores de Meta y lo almacena localmente.
     *
     * @param string $mediaId ID del archivo multimedia en Meta.
     * @param string $accessToken Token de acceso (tenant).
     * @param string $mimeType El tipo MIME del archivo (ej. image/jpeg, audio/ogg).
     * @return string|null La URL pública local del archivo descargado, o null si falla.
     */
    public function downloadAndStoreMedia(string $mediaId, string $accessToken, string $mimeType): ?string
    {
        try {
            // 1. Obtener la URL del archivo desde Meta Graph API
            $response = Http::withToken($accessToken)
                ->get("https://graph.facebook.com/v19.0/{$mediaId}");

            if (!$response->successful() || !isset($response->json()['url'])) {
                return null;
            }

            $mediaUrl = $response->json()['url'];

            // 2. Descargar el archivo real usando el token (Meta requiere auth para descargar)
            $mediaResponse = Http::withToken($accessToken)->get($mediaUrl);

            if (!$mediaResponse->successful()) {
                return null;
            }

            // 3. Determinar la extensión del archivo a partir del MIME type
            $extension = $this->getExtensionFromMime($mimeType);
            
            // 4. Guardar en Storage (public disk)
            $filename = 'whatsapp_media/' . Str::uuid() . '.' . $extension;
            
            Storage::disk('public')->put($filename, $mediaResponse->body());

            // Devolver la ruta relativa (o URL) para la base de datos
            return '/storage/' . $filename;
            
        } catch (\Exception $e) {
            \Log::error("Error descargando media de WhatsApp: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Mapeo simple de MIME a extensión.
     */
    private function getExtensionFromMime(string $mimeType): string
    {
        $map = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'audio/ogg; codecs=opus' => 'ogg',
            'audio/ogg' => 'ogg',
            'audio/aac' => 'aac',
            'audio/mp4' => 'm4a',
            'audio/amr' => 'amr',
            'video/mp4' => 'mp4',
            'application/pdf' => 'pdf',
        ];

        // Si incluye parámetros (como codecs=opus), buscar primero el match exacto
        if (isset($map[$mimeType])) {
            return $map[$mimeType];
        }

        // Si no, limpiar el mime type base
        $baseMime = explode(';', $mimeType)[0];
        return $map[$baseMime] ?? 'bin';
    }
}
