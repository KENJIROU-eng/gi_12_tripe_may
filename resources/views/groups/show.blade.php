<x-app-layout>

    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>


    <div class="relative flex bg-yellow-100 w-full h-16 items-center justify-between">
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
            @php $isMine = $message->user_id === auth()->id();@endphp

                @if ($isMine)
                    <div cid="message-{{ $message->id }}" class="flex justify-end items-end" oncontextmenu="openCustomMenu(event, {{ $message->id }})">


                        <div class="text-xs text-right mt-1 text-gray-400 mr-2">
                            {{ $message->created_at->format('H:i') }}
                        </div>
                        <div class="bg-green-300 rounded-2xl p-3 max-w-[70%] shadow ">
                            @if ($message->message)
                                <div style="word-break: break-word; overflow-wrap: break-word;">{{$message->message }}</div>
                            @endif
                        </div>
                        @if ($message->image_url)
                            <img src="{{ $message->image_url }}" class="mt-2 max-w-xs rounded-lg" download>
                        @endif
                    </div>
                @else
                <div>
                    <div class="flex items-start">
                        <img src="{{ $message->user->avatar_url ?? asset('images/user.png') }}" class="w-8 h-8 rounded-full mt-1" alt="{{ $message->user->name }}">
                        <div class="flex space-x-2 items-end">
                            <div class="max-w-[70%]">
                                <div class="text-sm text-gray-600 font-medium">{{ $message->user->name }}</div>
                                <div class="bg-white rounded-2xl p-3 shadow">
                                    @if ($message->message)
                                    <div style="word-break: break-word; overflow-wrap: break-word;">{{ $message->message }}</div>
                                    @endif
                                </div>
                                @if ($message->image_url)
                                <img src="{{ $message->image_url }}" class="mt-2 max-w-xs rounded-lg" download>
                                @endif
                            </div>
                            <div class="text-xs text-gray-400 items-end">
                                {{ $message->created_at->format('H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
        @endforeach
    </div>

    <ul id="custom-menu"
    class="absolute hidden bg-gray-100  rounded shadow z-50">
    <li id="edit-item" class="p-1 m-2 hover:bg-gray-200 cursor-pointer">
        Edit
    </li>
    <li id="delete-item" class="p-1 hover:bg-gray-200 cursor-pointer">
        Delete
    </li>
</ul>

<script>
    let currentMessageId = null;

    function openCustomMenu(event, messageId) {
        event.preventDefault();

        currentMessageId = messageId;

        const menu = document.getElementById('custom-menu');
        menu.style.top = event.clientY + 'px';
        menu.style.left = event.clientX + 'px';
        menu.classList.remove('hidden');
    }

    document.getElementById('edit-item').addEventListener('click', () => {
        if (currentMessageId) {
            window.location.href = `/chat/${currentMessageId}/edit`;
        }
    });

    document.getElementById('delete-item').addEventListener('click', () => {
        if (currentMessageId && confirm('本当に削除しますか？')) {
            fetch(`/chat/${currentMessageId}/delete`, {
                method:'DELETE',
                headers:{'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            }).then(response=>{
                if (response.ok) {
                    location.reload();
                }
            });
        }
    });

    document.addEventListener('click', () => {
        document.getElementById('custom-menu').classList.add('hidden');
    });
</script>


    <form id="chat-form" action="{{ route('message.send')}}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 p-2 border-t mt-4 bg-white fixed bottom-0 left-0 right-0 z-50">
    @csrf
        <input type="hidden" name="group_id" value="{{ $group->id }}">
        <textarea id="message-input" name="message" rows="1" placeholder="message..." class="flex-1 p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300 resize-none max-h-[6rem] overflow-y-auto leading-relaxed text-sm sm:text-base"></textarea>
        <input type="file" name="image" accept="image/*" class="hidden" id="image-upload">
        <label for="image-upload" class="cursor-pointer">
            <i class="fa-solid fa-image text-xl text-gray-500 hover:text-blue-500"></i>
        </label>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Send
        </button>
    </form>
</x-app-layout>

