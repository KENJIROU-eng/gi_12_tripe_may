<x-app-layout>

    {{-- <div style="background-image: url('/images/pexels-quintingellar-844167.jpg'); background-size: cover; background-position: center"> --}}
        <div class= "mt-5">
            <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8">
                <div class="bg-white/95 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class=" text-black dark:text-gray-100">
                        {{-- title --}}
                        <div class="relative flex items-center justify-center h-16 my-3 sm:my-5">
                            <h1 class=" text-3xl lg:text-5xl xl:text-6xl font-semibold font-bree absolute left-1/2 transform -translate-x-1/2">Group List</h1>
                            <a href="{{ route('groups.create')}}" class="absolute right-3 text-xs sm:text-base lg:text-lg font-medium text-teal-500 hover:text-teal-700 bg-white border border-teal-500 hover:border-teal-700 rounded-3xl shadow-md p-1 sm:p-2"><i class="fa-solid fa-plus"></i> New Group</a>
                        </div>
                        <div class="p-[2px] bg-gradient-to-r from-stone-200 via-stone-400 to-stone-200"></div>
                        {{-- contents --}}
                        <div class="mx-auto overflow-y-auto max-h-[670px] mt-8 flex-1">
                            @if (!($groups->isNotEmpty()))
                                <div class="text-center text-lg my-60">
                                    <h2 class="mb-4 text-gray-500">No group created yet.</h2>
                                    <div class="text-blue-500">
                                        <a href="{{ route('groups.create') }}">
                                            <i class="fa-solid fa-plus"></i>
                                            add group
                                        </a>
                                    </div>
                                </div>
                            @else
                                @if(($latestMessages->isNotEmpty()))
                                    @foreach ($latestMessages as $message)
                                        <div class="flex items-center justify-between fonu-serif bg-white rounded-lg shadow p-5 mb-3 mx-3 hover:bg-amber-100 transition">
                                            <a href="{{ route('message.show', $message->group->id)}}" class="flex items-center space-x-4 w-full ml-2">
                                                @if ($message->group->image)
                                                    <img src="{{ asset('storage/' . $message->group->image)}}" alt="Group Image" class="w-14 h-14 rounded-full object-cover">
                                                @else
                                                <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($message->group->name, 0, 1)) }}
                                                </div>
                                                @endif
                                                <div class="flex flex-row sm:flex-row sm:items-center sm:justify-center text-center sm:space-x-2">
                                                    @if ($message->group->users->count() == 2)
                                                        @if (($message->group->name == Auth::User()->name) || ($message->group->user_id == Auth::User()->id))
                                                            @foreach ($message->group->users as $user)
                                                                @if (($message->group->name == $user->name) || ($message->group->user_id == $user->id))
                                                                    @if (Auth::User()->id != $user->id)
                                                                        <p class="font-semibold text-xl md:text-2xl truncate">{{ $user->name }}</p>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <p class="font-semibold text-xl md:text-2xl md:hidden truncate max-w-[160px]">{{ \Illuminate\Support\Str::limit($message->group->name, 10) }}</p>
                                                            <!-- PC以上は制限なし -->
                                                            <p class="font-semibold text-xl md:text-2xl hidden md:block truncate">{{ $message->group->name }}</p>
                                                        @endif
                                                    @else
                                                        <p class="font-semibold text-xl md:text-2xl md:hidden truncate max-w-[160px]">{{ \Illuminate\Support\Str::limit($message->group->name, 10) }}</p>
                                                        <!-- PC以上は制限なし -->
                                                        <p class="font-semibold text-xl md:text-2xl hidden md:block truncate">{{ $message->group->name }}</p>
                                                    @endif
                                                    <p class="text-lg ml-3">({{ $message->group->members->count() }})</p>
                                                </div>
                                                @if ($nonReadCount)
                                                    @if ($nonReadCount[$message->group->id] > 0)
                                                        <div class="flex items-center justify-center w-6 h-6 bg-red-500 text-white rounded-full">
                                                            <p class="text-sm font-semibold">{{ $nonReadCount[$message->group->id] }}</p>
                                                        </div>
                                                    @endif
                                                @endif
                                            </a>
                                            <div x-data="{ showEditModal: false, showDeleteModal: false }" class="mb-6 relative">
                                                <div class="flex items-center justify-between  rounded">
                                                    <div class="relative" x-data="{ open: false }">
                                                        <button @click="open = !open" class="text-gray-600 hover:text-black focus:outline-none">
                                                            <i class="fa-solid fa-ellipsis text-xl"></i>
                                                        </button>
                                                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-32 bg-white border rounded shadow z-50">
                                                            <button @click="showEditModal = true" class="block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-100">
                                                                Edit
                                                            </button>
                                                            <button @click="showDeleteModal = true" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- modal--}}
                                                @include('groups.modals', ['group' => $message->group, 'users' => $users])
                                            </div>
                                        </div>
                                    @endforeach
                                    @foreach ($groups_filtered as $group)
                                        <div class="flex items-center justify-between fonu-serif bg-white rounded-lg shadow-lg p-5 mb-3 mx-3 hover:bg-orange-100 transition">
                                            <a href="{{ route('message.show', $group->id)}}" class="flex items-center space-x-4 w-full ml-2">
                                                @if ($group->image)
                                                    <img src="{{ asset('storage/' . $group->image)}}" alt="Group Image" class="w-14 h-14 rounded-full object-cover">
                                                @else
                                                <div class="w-14 h-14 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($group->name, 0, 1)) }}
                                                </div>
                                                @endif
                                                <div class="flex flex-row sm:flex-row sm:items-center sm:justify-center text-center sm:space-x-2 ">
                                                    <p class="font-semibold text-xl md:text-2xl md:hidden truncate max-w-[160px]">{{ \Illuminate\Support\Str::limit($group->name, 10) }}</p>
                                                    <!-- PC以上は制限なし -->
                                                    <p class="font-semibold text-xl md:text-2xl hidden md:block truncate">{{ $group->name }}</p>
                                                    <p class="text-lg ml-3">({{ $group->members->count() }})</p>
                                                </div>
                                                @if ($nonReadCount)
                                                    @if ($nonReadCount[$group->id] > 0)
                                                        <div class="flex items-center justify-center w-6 h-6 bg-red-500 text-white rounded-full">
                                                            <p class="text-sm font-semibold">{{ $nonReadCount[$group->id] }}</p>
                                                        </div>
                                                    @endif
                                                @endif
                                            </a>
                                            <div x-data="{ showEditModal: false, showDeleteModal: false }" class="mb-6 relative">
                                                <div class="flex items-center justify-between  rounded">
                                                    <div class="relative" x-data="{ open: false }">
                                                        <button @click="open = !open" class="text-gray-600 hover:text-black focus:outline-none">
                                                            <i class="fa-solid fa-ellipsis text-xl"></i>
                                                        </button>
                                                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-32 bg-white border rounded shadow z-50">
                                                            <button @click="showEditModal = true" class="block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-100">
                                                                Edit
                                                            </button>
                                                            <button @click="showDeleteModal = true" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- modal--}}
                                                @include('groups.modals', ['group' => $group, 'users' => $users])
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    @foreach ($groups_filtered as $group)
                                        <div class="flex items-center justify-between fonu-serif bg-white rounded-lg shadow p-5 mb-3 mx-3 hover:bg-orange-100 transition">
                                            <a href="{{ route('message.show', $group->id)}}" class="flex items-center space-x-4 w-full ml-2">
                                                @if ($group->image)
                                                    <img src="{{ asset('storage/' . $group->image)}}" alt="Group Image" class="w-14 h-14 rounded-full object-cover">
                                                @else
                                                <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($group->name, 0, 1)) }}
                                                </div>
                                                @endif
                                                <div class="flex flex-row sm:flex-row sm:items-center sm:justify-center text-center sm:space-x-2 ">
                                                    <p class="font-semibold text-xl md:text-2xl md:hidden truncate max-w-[160px]">{{ \Illuminate\Support\Str::limit($group->name, 10) }}</p>
                                                    <!-- PC以上は制限なし -->
                                                    <p class="font-semibold text-xl md:text-2xl hidden md:block truncate">{{ $group->name }}</p>
                                                    <p class="text-lg ml-3">({{ $group->members->count() }})</p>
                                                </div>
                                                @if ($nonReadCount)
                                                    @if ($nonReadCount[$group->id] > 0)
                                                        <div class="flex items-center justify-center w-6 h-6 bg-red-500 text-white rounded-full">
                                                            <p class="text-sm font-semibold">{{ $nonReadCount[$group->id] }}</p>
                                                        </div>
                                                    @endif
                                                @endif
                                            </a>
                                            <div x-data="{ showEditModal: false, showDeleteModal: false }" class="mb-6 relative">
                                                <div class="flex items-center justify-between  rounded">
                                                    <div class="relative" x-data="{ open: false }">
                                                        <button @click="open = !open" class="text-gray-600 hover:text-black focus:outline-none">
                                                            <i class="fa-solid fa-ellipsis text-xl"></i>
                                                        </button>
                                                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-32 bg-white border rounded shadow z-50">
                                                            <button @click="showEditModal = true" class="block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-100">
                                                                Edit
                                                            </button>
                                                            <button @click="showDeleteModal = true" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- modal--}}
                                                @include('groups.modals', ['group' => $group, 'users' => $users])
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- </div> --}}
</x-app-layout>
