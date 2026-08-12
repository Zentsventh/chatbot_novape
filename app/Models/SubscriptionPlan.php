<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'currency',
        'billing_cycle',
        'max_agents',
        'max_channels',
        'message_limit_per_month',
        'ai_engine_allowed',
        'has_crm',
        'has_appointments',
        'has_sequences',
        'has_internal_chat',
        'has_auto_assignment',
        'features',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'features' => 'json',
        'is_active' => 'boolean',
        'has_crm' => 'boolean',
        'has_appointments' => 'boolean',
        'has_sequences' => 'boolean',
        'has_internal_chat' => 'boolean',
        'has_auto_assignment' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
