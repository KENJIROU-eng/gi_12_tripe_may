<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
// use App\Models\Message;

class MessageNotification extends Component
{
    // public array $nonReadCount = [];
    // public int $nonReadCount_total = 0;
    // public $count = 0;
    // public $groups = [];
    // public $groupIds = [];

    // public function mount($groups = [], $groupIds = [])
    // {
    //     $this->groups = $groups ?? [];
    //     $this->groupIds = $groupIds ?? [];
    // }

    // #[On('message-notice')]
    // public function refreshMessages()
    // {
        // $groupId = $params['groupId'] ?? null;
        // $userId = $params['userId'] ?? null;
        // $messageIds = Message::where('group_id', $groupId)->pluck('id');
        // $count = ReadMessage::whereIn('message_id', $messageIds)
        //     ->where('user_id', $userId)
        //     ->whereNull('read_at')
        //     ->count();

        // $this->count = $count;
        // $this->nonReadCount[$groupId] = $count;
        // $this->nonReadCount_total = array_sum($this->nonReadCount);
        // $this->count = Message::all()->count();
    //     $this->count = $this->count + 1;
    // }

    // public function render()
    // {
        // return view('livewire.message-notification',['count' => $this->count]);
        // 'nonReadCount' => $this->nonReadCount,
        // 'count' => $this->count,
        // 'groups' => $this->groups,
        // 'groupIds' => $this->groupIds,
        // 'nonReadCount_total' => $this->nonReadCount_total,
        // ]);
    // }
    public $count = 0;

    #[On('message-notice')]
    public function refreshMessages()
    {
        logger('refreshMessages 発火！');
        $this->count++;
    }

    public function render()
    {
        return view('livewire.message-notification', ['count' => $this->count]);
    }
}
