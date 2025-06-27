<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostLike extends Component
{
    public Post $post;
    public bool $isLiked = false;
    public int $likeCount = 0;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->isLiked = $post->likes->contains('user_id', Auth::id());
        $this->likeCount = $post->likes()->count();
    }

    public function toggleLike()
    {
        if ($this->isLiked) {
            $this->post->likes()->where('user_id', Auth::id())->delete();
            $this->isLiked = false;
        } else {
            $this->post->likes()->create([
                'user_id' => Auth::id(),
            ]);
            $this->isLiked = true;
        }

        $this->likeCount = $this->post->likes()->count();
    }

    public function render()
    {
        return view('livewire.post-like');
    }
}
