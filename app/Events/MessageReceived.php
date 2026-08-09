<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class MessageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('tenant.' . $this->message->tenant_id),
        ];
    }

    public function broadcastWith(): array
    {
        // Extract mentions
        preg_match_all('/@([a-zA-Z0-9_]+)/', $this->message->content, $matches);
        $mentions = $matches[1] ?? [];

        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'content' => $this->message->content,
            'direction' => $this->message->direction,
            'isInternalNote' => (bool)$this->message->is_internal_note,
            'mentions' => $mentions,
            'created_at' => $this->message->created_at->toIso8601String(),
        ];
    }
}
