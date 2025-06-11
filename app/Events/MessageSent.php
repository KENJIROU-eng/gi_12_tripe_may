<?php

// チャットの新しいメッセージをリアルタイムに他のクライアントに通知するためのクラス

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
    // public $user_id;
    // public $user_name;
    // public $user_avatar;
    // public $image_url;
    // public $time;
    // public $group_id;
    /**
     * Create a new event instance.
     */
    public function __construct($user,$message)
    {
        $this->message = $message;
        // $this->user_id = $user->id;
        // $this->user_name =$user->name;
        // $this->user_avatar = $user->avatar ?? null;
        // $this->group_id = $message->group_id;
        // $this->image_url = $image_url;
        // $this->time = now()->format('H:i');//ex.12:34
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array //ブロードキャストするチャンネルを
    {
        return [
            new PrivateChannel('group.' . $this->message->group_id),
        ];
    }

    public function broadcastAs(){
        return 'message.sent';
    }

    public function broadcastWith(){
        return [
            'message' => ['text' => $this->message->message,],
            'user_id' => $this->message->user_id,
            'user_name' => $this->message->user->name,
            'user_avatar' => $this->message->user->user_avatar,
            'image_url' => $this->image_url,
            'time' => $this->message->created_at->format('H:i'),//ex12:55
        ];
    }
}

//1.メッセージ送信時に broadcast(new MessageSent($user, $message)) が呼ばれる。

//2.指定された group.{id} チャンネルに "message.sent" イベントがリアルタイム送信される。

//3.クライアント側（JavaScript）が .listen('.message.sent') で受信。

//4.broadcastWith() の内容がクライアント側に届き、チャット画面にメッセージが追加される。


