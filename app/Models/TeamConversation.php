<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'is_group',
    ];

    protected $casts = [
        'is_group' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'team_conversation_user');
    }

    public function messages()
    {
        return $this->hasMany(TeamMessage::class);
    }
}
