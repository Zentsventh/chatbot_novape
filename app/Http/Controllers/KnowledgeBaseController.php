<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBase;
use App\Models\KnowledgeChunk;
use App\Services\GeminiService;
use App\Jobs\ProcessKnowledgeChunks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Log;

class KnowledgeBaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $knowledge = KnowledgeBase::where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return response()->json($knowledge);
    }

    /**
     * Store a newly uploaded PDF resource in storage.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        $tenantId = $request->user()->tenant_id;
        $file = $request->file('file');

        try {
            // Parse PDF
            $parser = new Parser();
            $pdf = $parser->parseFile($file->getPathname());
            $text = $pdf->getText();

            // Guardar en la DB
            $kb = KnowledgeBase::create([
                'tenant_id' => $tenantId,
                'name' => $file->getClientOriginalName(),
                'type' => 'pdf',
                'content' => $text,
                'metadata' => [
                    'size' => $file->getSize(),
                    'pages' => count($pdf->getPages())
                ],
                'status' => 'processing'
            ]);

            // Generar Chunks y Vectores de forma asíncrona
            ProcessKnowledgeChunks::dispatch($kb->id, $text);

            return response()->json(['success' => true, 'data' => $kb], 201);

        } catch (\Exception $e) {
            Log::error('KnowledgeBase upload failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch URL and store as text.
     */
    public function storeUrl(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:2000',
        ]);

        $tenantId = $request->user()->tenant_id;
        $url = $request->input('url');

        try {
            $host = parse_url($url, PHP_URL_HOST);
            $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
            if (!in_array($scheme, ['http', 'https'], true) || !$host || parse_url($url, PHP_URL_USER) || parse_url($url, PHP_URL_PASS)) {
                return response()->json(['success' => false, 'message' => 'URL no permitida.'], 422);
            }

            $port = parse_url($url, PHP_URL_PORT) ?: ($scheme === 'https' ? 443 : 80);
            if (!in_array($port, [80, 443], true)) {
                return response()->json(['success' => false, 'message' => 'Puerto no permitido.'], 422);
            }

            $addresses = filter_var($host, FILTER_VALIDATE_IP) ? [$host] : array_column(dns_get_record($host, DNS_A | DNS_AAAA) ?: [], 'ip');
            if (!$addresses || count($addresses) !== count(array_filter($addresses, fn ($ip) => filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)))) {
                return response()->json(['success' => false, 'message' => 'Destino no permitido.'], 422);
            }
            if (!extension_loaded('curl')) {
                throw new \RuntimeException('La verificación segura de URL requiere cURL.');
            }

            $response = Http::timeout(10)->withOptions([
                'allow_redirects' => false,
                'stream' => true,
                'curl' => [CURLOPT_RESOLVE => ["{$host}:{$port}:{$addresses[0]}"]],
            ])->get($url);
            
            if (!$response->successful()) {
                throw new \Exception('Failed to fetch URL. Status: ' . $response->status());
            }

            $html = $response->toPsrResponse()->getBody()->read(2 * 1024 * 1024 + 1);
            if (strlen($html) > 2 * 1024 * 1024) {
                throw new \RuntimeException('El contenido supera el límite de 2 MB.');
            }
            
            // Extracción de texto desde HTML
            $text = strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html));
            $text = preg_replace('/\s+/', ' ', $text);

            $kb = KnowledgeBase::create([
                'tenant_id' => $tenantId,
                'name' => $url,
                'type' => 'url',
                'content' => trim($text),
                'metadata' => [
                    'length' => strlen($text)
                ],
                'status' => 'processing'
            ]);

            // Generar Chunks y Vectores de forma asíncrona
            ProcessKnowledgeChunks::dispatch($kb->id, $text);

            return response()->json(['success' => true, 'data' => $kb], 201);

        } catch (\Exception $e) {
            Log::error('KnowledgeBase URL failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $tenantId = $request->user()->tenant_id;

        $kb = KnowledgeBase::where('tenant_id', $tenantId)->findOrFail($id);

        // Borrar chunks asociados
        KnowledgeChunk::where('knowledge_base_id', $kb->id)->delete();
        $kb->delete();

        return response()->json(['success' => true]);
    }
}
