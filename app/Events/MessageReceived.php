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
            new PrivateChannel('tenant.' . $this->message->tenant_id),
        ];
    }

    public function broadcastWith(): array
    {
        // Extract mentions
        preg_match_all('/@([a-zA-Z0-9_]+)/', $this->message->content ?? '', $matches);
        $mentions = $matches[1] ?? [];

        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'direction' => $this->message->direction,
            'messageType' => $this->message->message_type,
            'content' => $this->message->content,
            'mediaUrl' => $this->message->media_url,
            'mediaMimeType' => $this->message->media_mime_type,
            'isInternalNote' => (bool)$this->message->is_internal_note,
            'mentions' => $mentions,
            'time' => $this->message->created_at->format('H:i'),
            'status' => $this->message->status,
            'created_at' => $this->message->created_at->toIso8601String(),
        ];
    }
}
