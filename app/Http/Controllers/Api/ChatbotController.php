<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function index()
    {
        $settings = \App\Models\ChatbotKnowledge::first();

        return response()->json([
            'system_prompt' => $settings->system_prompt ?? '',
            'temperature' => (float) ($settings->ai_temperature ?? 0.7),
            'is_active' => (bool) ($settings->is_bot_active ?? true)
        ]);
    }

    public function update(Request $request)
    {
        $settings = \App\Models\ChatbotKnowledge::first();
        if ($settings) {
            $settings->update([
                'system_prompt' => $request->system_prompt,
                'ai_temperature' => $request->temperature,
                'is_bot_active' => $request->is_active,
            ]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
