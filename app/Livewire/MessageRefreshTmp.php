<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Message;
use App\Models\ReadMessage;

class MessageRefreshTmp extends Component
{

    // public array $nonReadCount = [];
    // public int $nonReadCount_total = 0;
    public $count = 0;
    // public $groups = [];
    // public $groupIds = [];

    // public function mount($groups = [], $groupIds = [])
    // {
    //     $this->groups = $groups ?? [];
    //     $this->groupIds = $groupIds ?? [];
    // }
    
    #[On('refresh')]
    public function refreshMessages()
    {
        Log::debug('Livewire: refresh イベント受信 ✅');
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
        $this->count = Message::all()->count();
    }

    public function render()
    {
        return view('livewire.message-refresh-tmp', [
        // 'nonReadCount' => $this->nonReadCount,
        'count' => $this->count,
        // 'groups' => $this->groups,
        // 'groupIds' => $this->groupIds,
        // 'nonReadCount_total' => $this->nonReadCount_total,
        ]);
    }
}
