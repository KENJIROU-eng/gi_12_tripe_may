<div class="flex items-center">
    <div class="ml-4 pointer-events-auto">
        <button wire:click="toggleLike" class="focus:outline-none">
            @if ($isLiked)
                <i class="fa-solid fa-heart text-red-500 text-xl mr-2"></i>
            @else
                <i class="fa-regular fa-heart text-white hover:text-red-500 text-xl mr-2"></i>
            @endif
        </button>
    </div>

    <div class="mr-4 text-white">
        {{ $likeCount }}
    </div>
</div>
