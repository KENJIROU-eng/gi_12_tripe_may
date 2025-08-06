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
use App\Models\Group;

class MessageDelete implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    /**
     * Create a new event instance.
     */
    public function __construct($message)
    {
        $this->message = $message;
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
        return 'message.delete';
    }

    public function broadcastWith(){
        $group = Group::findOrFail($this->message->group_id);
        $groupMembers = $group->members()->get();
        $groupMember_name = [];
        foreach ($groupMembers as $groupMember) {
            $groupMember_name[] = $groupMember->user->name;
        }
        return [
            'message' => ['text' => $this->message->message,],
            'message_id' => $this->message->id,
            'user_id' => $this->message->user_id,
            'user_name' => $this->message->user->name,
            'user_avatar' => $this->message->user->user_avatar,
            'image_url' => $this->message->image_url ?? '',
            'groupMember_name' => $groupMember_name,
            'group_name' => $group->name,
            'time_hm' => $this->message->created_at->format('H:i'),
            'time_ymd' => $this->message->created_at->format('Y-m-d'),
        ];
    }
}

//1.メッセージ送信時に broadcast(new MessageSent($user, $message)) が呼ばれる。

//2.指定された group.{id} チャンネルに "message.sent" イベントがリアルタイム送信される。

//3.クライアント側（JavaScript）が .listen('.message.sent') で受信。

//4.broadcastWith() の内容がクライアント側に届き、チャット画面にメッセージが追加される。


