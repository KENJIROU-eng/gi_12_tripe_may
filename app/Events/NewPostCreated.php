<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewPostCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;

    /**
     * Create a new event instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     *
     */
    // ブロードキャストするチャンネル名
    public function broadcastOn()
    {
        Log::debug('broadcastOn called, post id:', ['id' => $this->post->id ?? 'null']);
        return [
        new PrivateChannel('posts.' . $this->post->id),
        // new Channel('posts'), // 公開チャンネル
        ];
        // return new PrivateChannel('posts.' . $this->post->id);
    }
    // ブロードキャストで送るデータ
    public function broadcastWith()
    {
        return [
            'id' => $this->post->id,
            'title' => $this->post->title,
        ];
    }

    public function broadcastAs()
    {
        return 'NewPostCreated';
    }
}
