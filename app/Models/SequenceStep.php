<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SequenceStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'sequence_id',
        'step_order',
        'delay_minutes',
        'message_content',
        'message_type',
        'media_url',
        'condition',
    ];

    protected $casts = [
        'condition' => 'array',
    ];

    public function sequence(): BelongsTo
    {
        return $this->belongsTo(Sequence::class);
    }
}
