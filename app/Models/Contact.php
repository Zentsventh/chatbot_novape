<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'phone_number',
        'messenger_id',
        'instagram_id',
        'email',
        'company',
        'profile_picture_url',
        'notes',
        'metadata',
        'is_blocked',
        'assigned_user_id',
        'first_interaction_at',
        'last_interaction_at',
    ];

    protected $casts = [
        'metadata' => 'json',
        'is_blocked' => 'boolean',
        'first_interaction_at' => 'datetime',
        'last_interaction_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Tags via polymorphic relationship.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
