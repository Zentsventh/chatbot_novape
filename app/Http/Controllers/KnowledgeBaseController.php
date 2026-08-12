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
            $response = Http::timeout(10)->get($url);
            
            if (!$response->successful()) {
                throw new \Exception('Failed to fetch URL. Status: ' . $response->status());
            }

            $html = $response->body();
            
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
