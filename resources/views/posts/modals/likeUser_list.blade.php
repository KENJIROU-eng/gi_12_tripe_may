{{-- modal content --}}
<div x-show="open" class="fixed inset-0 bg-gray-500/75 z-10 flex items-center justify-center w-full">
    <div class="bg-white p-6 rounded shadow max-w-md w-full max-h-[500px] overflow-y-auto">
        {{-- header --}}
        <div class="flex px-6 py-4 text-center">
            <h1 class="text-2xl font-bold">Users who like this post</h1>
            <a href="{{ route('post.list') }}" class="ml-auto">
                <i class="fa-solid fa-xmark text-red-500 text-2xl"></i>
            </a>
        </div>
        <hr class="border-green-500 border-1">
        {{-- body --}}
        <div class="mx-auto h-full mt-8">
            @foreach ($post->likes as $like)
                <div class="flex items-center justify-between bg-white rounded-lg shadow p-4 mb-4 hover:bg-gray-50 transition">
                    <a href="" class="flex items-center space-x-4 w-full ml-2">
                        @if ($like->user->avatar)
                            <img src="{{ $like->user->avatar }}" alt="{{ $like->user->name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($like->user->name, 0, 1)) }}
                        </div>
                        @endif

                        <div class="flex flex-row sm:flex-row sm:items-center sm:justify-center text-center sm:space-x-2 ">
                            <p class="font-semibold text-2xl truncate ">{{ $like->user->name }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
