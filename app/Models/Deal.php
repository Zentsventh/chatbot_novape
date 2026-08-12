<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Deal extends Model
{
    protected $fillable = [
        'tenant_id',
        'contact_id',
        'assigned_user_id',
        'title',
        'description',
        'value',
        'currency',
        'stage',
        'probability',
        'expected_close_date',
        'closed_at',
        'lost_reason',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
        'expected_close_date' => 'date',
        'closed_at' => 'datetime',
        'value' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * Tags via polymorphic relationship.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
