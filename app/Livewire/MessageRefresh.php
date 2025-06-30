<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\ReadMessage;
use Illuminate\Support\Facades\Auth;

class PostLike extends Component
{
    public Post $post;

    protected $listeners = ['refreshMessages' => '$refresh'];

    public function render()
    {
        return view('livewire.post-like');
    }
}
