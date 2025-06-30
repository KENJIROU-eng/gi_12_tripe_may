<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\ReadMessage;

class MessageRefresh extends Component
{

    public array $nonReadCount = [];
    public int $nonReadCount_total = 0;
    public $count = 0;
    public $groups = [];
    public $groupIds = [];
    // protected $listeners = ['refreshMessages'];

    protected $listeners = ['refresh' => 'refreshMessages'];

    public function mount($groups, $groupIds)
    {
        $this->groups = $groups;
        $this->groupIds = $groupIds;
    }

    public function refreshMessages($params)
    {
        $groupId = $params['groupId'] ?? null;
        $userId = $params['userId'] ?? null;
        logger("refreshMessages called with groupId = $groupId, userId = $userId");

        $messageIds = Message::where('group_id', $groupId)->pluck('id');

        $count = ReadMessage::whereIn('message_id', $messageIds)
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->count();

        $this->count = $count; 

        $this->nonReadCount[$groupId] = $count;

        $this->nonReadCount_total = array_sum($this->nonReadCount);
    }

    public function render()
    {
        return view('livewire.message-refresh')
            ->with('nonReadCount', $this->nonReadCount)
            ->with('count', $this->count)
            ->with('groups', $this->groups)
            ->with('groupIds', $this->groupIds)
            ->with('nonReadCount_total', $this->nonReadCount_total);
    }
    }
