<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatbotKnowledge;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        $settings = ChatbotKnowledge::where('tenant_id', $tenantId)->first();

        if (!$settings) {
            return response()->json([
                'system_prompt' => '',
                'temperature' => 0.7,
                'is_active' => true,
                'bot_name' => 'Asistente Virtual',
            ]);
        }

        return response()->json([
            'system_prompt' => $settings->system_prompt ?? '',
            'temperature' => (float) ($settings->ai_temperature ?? 0.7),
            'is_active' => (bool) ($settings->is_bot_active ?? true),
            'bot_name' => $settings->bot_name ?? 'Asistente Virtual',
        ]);
    }

    public function update(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $validated = $request->validate([
            'system_prompt' => 'nullable|string|max:10000',
            'temperature' => 'nullable|numeric|min:0|max:1',
            'is_active' => 'nullable|boolean',
            'bot_name' => 'nullable|string|max:100',
        ]);

        $settings = ChatbotKnowledge::updateOrCreate(
            ['tenant_id' => $tenantId],
            [
                'system_prompt' => $validated['system_prompt'] ?? null,
                'ai_temperature' => $validated['temperature'] ?? 0.7,
                'is_bot_active' => $validated['is_active'] ?? true,
                'bot_name' => $validated['bot_name'] ?? 'Asistente Virtual',
            ]
        );

        return response()->json(['success' => true]);
    }
}
