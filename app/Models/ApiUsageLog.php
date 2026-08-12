<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiUsageLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'service',
        'endpoint',
        'tokens_input',
        'tokens_output',
        'tokens_total',
        'estimated_cost',
        'response_time_ms',
        'status_code',
        'is_successful',
        'error_message',
        'created_at',
    ];

    protected $casts = [
        'is_successful' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
