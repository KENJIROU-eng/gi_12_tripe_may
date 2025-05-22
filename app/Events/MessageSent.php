<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user_id;
    public $user_name;
    public $user_avatar;
    public $image_url;
    public $time;
    /**
     * Create a new event instance.
     */
    public function __construct($user,$message,$image_url = null)
    {
        $this->message = $message;
        $this->user_id = $user->id;
        $this->user_name =$user->name;
        $this->user_avatar = $user->avatar ?? null;
        $this->image_url = $image_url;
        $this->time = now()->format('H:i');//ex.12:34
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat'),
        ];
    }

    public function broadcastAs(){
        return 'message.sent';
    }
}
