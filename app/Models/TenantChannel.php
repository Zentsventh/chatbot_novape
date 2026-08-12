<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantChannel extends Model
{
    protected $fillable = [
        'tenant_id',
        'channel',
        'channel_name',
        'phone_number_id',
        'whatsapp_business_id',
        'page_id',
        'instagram_account_id',
        'access_token',
        'webhook_verify_token',
        'is_active',
        'is_verified',
        'last_webhook_at',
        'settings',
    ];

    protected $casts = [
        'settings' => 'json',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'last_webhook_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
