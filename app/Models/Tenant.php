<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subscription_plan_id',
        'company_name',
        'slug',
        'legal_name',
        'tax_id',
        'contact_email',
        'phone',
        'country',
        'timezone',
        'logo_url',
        'status',
        'trial_ends_at',
        'subscription_starts_at',
        'subscription_ends_at',
        'current_month_message_count',
        'message_count_reset_at',
        'api_token',
        'settings',
        'notes',
    ];

    protected $casts = [
        'settings' => 'json',
        'trial_ends_at' => 'datetime',
        'subscription_starts_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'message_count_reset_at' => 'datetime',
    ];

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function channels()
    {
        return $this->hasMany(TenantChannel::class);
    }

    public function chatbotKnowledge()
    {
        return $this->hasOne(ChatbotKnowledge::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
