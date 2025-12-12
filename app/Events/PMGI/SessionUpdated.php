<?php

namespace App\Events\PMGI;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class SessionUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public $sessionId, $role, $updated_by; 
    public $payload = [];

    public function __construct(string $sessionId, string $role, array $payload, string $updated_by)
    {
        $this->sessionId = $sessionId;
        $this->role = $role;
        $this->payload = $payload;
        $this->updated_by = $updated_by;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('pmgi.session.'.str_replace('/', '-', $this->sessionId)),
        ];
    }

    public function broadcastAs()
    {
        return 'pmgi.session.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'session_id' => $this->sessionId,
            'role' => $this->role,
            'payload' => $this->payload,
            'updated_by' => $this->updated_by,
        ];
    }
}
