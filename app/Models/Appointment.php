<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'tenant_id',
        'contact_id',
        'assigned_user_id',
        'title',
        'description',
        'scheduled_at',
        'duration_minutes',
        'status',
        'location',
        'notes',
        'reminder_sent_at',
        'cancelled_at',
        'cancel_reason',
        'source',
        'metadata',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'metadata' => 'json',
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
}
