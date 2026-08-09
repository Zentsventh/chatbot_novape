<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBase;
use App\Models\KnowledgeChunk;
use App\Services\GeminiService;
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
        $tenantId = 1; // Default a 1 por ahora

        $knowledge = KnowledgeBase::where('tenant_id', $tenantId)->orderBy('created_at', 'desc')->get();
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

        $tenantId = 1;
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
                'status' => 'processed'
            ]);

            // Generar Chunks y Vectores
            $this->processChunks($kb, $text);

            return response()->json(['success' => true, 'data' => $kb]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch URL and store as text.
     */
    public function storeUrl(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $tenantId = 1;
        $url = $request->url;

        try {
            $response = Http::timeout(10)->get($url);
            
            if (!$response->successful()) {
                throw new \Exception('Failed to fetch URL. Status: ' . $response->status());
            }

            $html = $response->body();
            
            // Extracción muy rudimentaria de texto desde HTML
            $text = strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html));
            $text = preg_replace('/\s+/', ' ', $text); // Limpiar espacios en blanco

            $kb = KnowledgeBase::create([
                'tenant_id' => $tenantId,
                'name' => $url,
                'type' => 'url',
                'content' => trim($text),
                'metadata' => [
                    'length' => strlen($text)
                ],
                'status' => 'processed'
            ]);

            // Generar Chunks y Vectores
            $this->processChunks($kb, $text);

            return response()->json(['success' => true, 'data' => $kb]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kb = KnowledgeBase::findOrFail($id);
        $kb->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Process text into chunks and generate embeddings.
     */
    private function processChunks(KnowledgeBase $kb, string $text)
    {
        $gemini = app(GeminiService::class);
        
        // Simple chunking strategy: split by double newlines (paragraphs)
        $paragraphs = preg_split('/\n\s*\n/', $text);
        
        // Combine small paragraphs to aim for ~500-1000 character chunks
        $chunks = [];
        $currentChunk = '';
        
        foreach ($paragraphs as $para) {
            $para = trim($para);
            if (empty($para)) continue;

            if (strlen($currentChunk) + strlen($para) > 1000) {
                if (!empty($currentChunk)) {
                    $chunks[] = $currentChunk;
                }
                $currentChunk = $para;
            } else {
                $currentChunk .= (empty($currentChunk) ? '' : "\n\n") . $para;
            }
        }
        if (!empty($currentChunk)) {
            $chunks[] = $currentChunk;
        }

        // Generate embedding for each chunk and save
        foreach ($chunks as $chunkText) {
            if (strlen($chunkText) < 10) continue; // Skip very small meaningless chunks

            $embedding = $gemini->embedText($chunkText);
            
            if ($embedding) {
                KnowledgeChunk::create([
                    'knowledge_base_id' => $kb->id,
                    'content' => $chunkText,
                    'embedding' => $embedding
                ]);
            } else {
                Log::warning('Failed to generate embedding for chunk', ['kb_id' => $kb->id]);
            }
        }
    }
}
