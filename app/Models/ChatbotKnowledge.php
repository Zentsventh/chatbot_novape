<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotKnowledge extends Model
{
    protected $table = 'chatbot_knowledges';

    protected $fillable = [
        'tenant_id',
        'bot_name',
        'system_prompt',
        'business_hours',
        'catalog',
        'faqs',
        'custom_instructions',
        'welcome_message',
        'out_of_hours_message',
        'bot_paused_message',
        'quota_exceeded_message',
        'is_bot_active',
        'max_context_messages',
        'ai_temperature',
    ];

    protected $casts = [
        'business_hours' => 'json',
        'catalog' => 'json',
        'faqs' => 'json',
        'is_bot_active' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
