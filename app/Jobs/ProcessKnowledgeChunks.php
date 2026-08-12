<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\KnowledgeBase;
use App\Models\KnowledgeChunk;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Log;

class ProcessKnowledgeChunks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300; // 5 minutes max

    public function __construct(
        public int $knowledgeBaseId,
        public string $text,
    ) {}

    /**
     * Execute the job — split text into chunks, generate embeddings, and store.
     */
    public function handle(GeminiService $gemini): void
    {
        $kb = KnowledgeBase::find($this->knowledgeBaseId);
        if (!$kb) return;

        try {
            // Simple chunking strategy: split by double newlines (paragraphs)
            $paragraphs = preg_split('/\n\s*\n/', $this->text);
            
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
            $successCount = 0;
            foreach ($chunks as $chunkText) {
                if (strlen($chunkText) < 10) continue;

                $embedding = $gemini->embedText($chunkText, $this->knowledgeBase->tenant_id);
                
                if ($embedding) {
                    KnowledgeChunk::create([
                        'knowledge_base_id' => $kb->id,
                        'content' => $chunkText,
                        'embedding' => $embedding
                    ]);
                    $successCount++;
                } else {
                    Log::warning('Failed to generate embedding for chunk', ['kb_id' => $kb->id]);
                }
            }

            // Update status to processed
            $kb->update([
                'status' => 'processed',
                'metadata' => array_merge($kb->metadata ?? [], [
                    'chunks_count' => $successCount,
                    'processed_at' => now()->toIso8601String(),
                ]),
            ]);

            Log::info('KnowledgeBase processed', ['kb_id' => $kb->id, 'chunks' => $successCount]);

        } catch (\Exception $e) {
            Log::error('ProcessKnowledgeChunks failed', [
                'kb_id' => $kb->id,
                'error' => $e->getMessage(),
            ]);

            $kb->update(['status' => 'failed']);
        }
    }
}
