<x-app-layout>

<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>


<div class="relative flex bg-gray-200 w-full h-16 items-center justify-between">
    <a href="{{ route('groups.index') }}"><i class="fa-regular fa-less-than text-black hover:text-gray-500 ml-4 text-xl"></i></a>
    <p class="text-2xl absolute left-1/2 transform -translate-x-1/2 font-semibold text-center">{{ $group->name}} ({{ $group->users->count()}})</p>
    <div x-data="{ open: false }" class="flex ">
        <button @click="open = !open" class="flex -space-x-4">
            @foreach ($group->users->take(3) as $user)
                <img src="{{ $user->avatar_url ?? asset('images/user.png') }}" class="w-9 h-9 rounded-full border-2 border-white hover:z-10" alt="{{ $user->name }}">
            @endforeach
            @if ($group->users->count() > 3)
                <div class="w-8 h-8 rounded-full bg-gray-300 text-xs text-white flex items-center justify-center border-2 border-white">
                    +{{ $group->users->count() - 3 }}
                </div>
            @endif
        </button>
        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-64 bg-white border rounded-lg shadow-lg z-50">
            <div class="p-4">
                <h2 class="text-sm font-semibold text-gray-600 mb-2">Group Member</h2>
                <ul class="space-y-6 max-h-60 overflow-y-auto">
                    @foreach ($group->users as $user)
                        <li class="flex items-center space-x-3">
                            <img src="{{ $user->avatar_url ?? asset('images/user.png') }}" class="w-8 h-8 rounded-full" alt="{{ $user->name }}">
                            <span class="text-sm">{{ $user->name }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!--chat-->
<div id="messages" data-group-id="{{ $group->id }}" class="overflow-y-scroll h-[calc(100vh-8rem)] px-4 pb-28 space-y-2">
    @foreach ($messages as $message)
        @php $isMine = $message->user_id === auth()->id();
            $hasImageOnly = !$message->message && $message->image_url;
        @endphp
        <div class="flex w-full {{ $isMine ? 'justify-end' : 'justify-start' }}">
            @if (!$isMine)
                <img src="{{ $message->user->avatar_url ?? asset('images/user.png') }}" class="w-8 h-8 rounded-full mt-1 mr-2" alt="{{ $message->user->name }}">
            @endif

            <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }}">
                @if (!$isMine)
                    <div class="text-sm text-gray-400 font-medium">
                        {{ $message->user->name }}
                    </div>
                @endif

                <div class="{{ $hasImageOnly ? '' : ( $isMine ? 'bg-green-300' : 'bg-white' )}} rounded-2xl p-3 max-w-[70%] shadow break-words {{ !$hasImageOnly ? 'whitespace-normal' : '' }}">
                    @if ($message->message)
                        <div>{{ $message->message }}</div>
                    @endif

                    @if ($message->image_url)
                        <img src="{{ $message->image_url }}" class="mt-2 max-w-xs rounded-lg">
                    @endif

                    <div class="text-xs text-right mt-1 text-gray-400">
                        {{ $message->created_at->format('H:i') }}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>



<form id="chat-form" enctype="multipart/form-data" class="flex items-center gap-2 p-2 border-t mt-4 bg-white fixed bottom-0 left-0 right-0 z-50">
    <input type="hidden" name="group_id" value="{{ $group->id }}">
    <input type="text" name="message" placeholder="message..." class="flex-1 p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
    <input type="file" name="image" accept="image/*" class="hidden" id="image-upload">
    <label for="image-upload" class="cursor-pointer">
        <i class="fa-solid fa-image text-xl text-gray-500 hover:text-blue-500"></i>
    </label>
    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Send
    </button>
</form>
</x-app-layout>

