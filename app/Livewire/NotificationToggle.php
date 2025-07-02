<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class NotificationToggle extends Component
{
    public $user;

    public function mount()
    {
        $this->user = auth()->user();
    }

    #[On('notification-enabled')]
    public function enableNotification()
    {
        $this->user->notification = true;
        $this->user->save();
    }

    #[On('notification-dismiss')]
    public function dismissNotification()
    {
        $this->user->notification = false;
        $this->user->save();
    }

    public function render()
    {
        return view('livewire.notification-toggle');
    }
}
