<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_conversation_id',
        'user_id',
        'content',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(TeamConversation::class, 'team_conversation_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
