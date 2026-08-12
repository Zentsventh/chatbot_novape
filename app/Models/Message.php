<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'conversation_id',
        'contact_id',
        'user_id',
        'channel',
        'direction',
        'message_type',
        'content',
        'media_url',
        'media_mime_type',
        'media_file_size',
        'is_ai_generated',
        'ai_engine_used',
        'ai_tokens_used',
        'ai_response_time_ms',
        'is_internal_note',
        'status',
        'error_message',
        'external_message_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
        'is_ai_generated' => 'boolean',
        'is_internal_note' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: only messages for a given tenant.
     */
    public function scopeForTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }
}
