<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'contact_id',
        'assigned_user_id',
        'channel',
        'status',
        'priority',
        'subject',
        'is_bot_paused',
        'bot_paused_at',
        'bot_paused_by',
        'auto_assigned',
        'assignment_rule_id',
        'last_message_at',
        'last_message_preview',
        'message_count',
        'unread_count',
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'bot_paused_at' => 'datetime',
        'last_message_at' => 'datetime',
        'resolved_at' => 'datetime',
        'is_bot_paused' => 'boolean',
        'auto_assigned' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function resolvedByUser()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function botPausedByUser()
    {
        return $this->belongsTo(User::class, 'bot_paused_by');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Scope: only conversations for the given tenant.
     */
    public function scopeForTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }
}
