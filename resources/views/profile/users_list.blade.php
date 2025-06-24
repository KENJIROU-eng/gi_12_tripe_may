<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">User List</h1>
                        <a href="{{ route('dashboard') }}" class="absolute right-40 inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold rounded-lg shadow">
                            <i class="fa-solid fa-house mr-2"></i>
                            Go to Dashboard
                        </a>
                    </div>
                    {{-- contents --}}
                    <div class="mx-auto h-full mt-8">
                        <form action="{{ route('profile.users.search') }}" method="get" class="w-full">
                            <div class="flex justify-center items-center mb-3 w-full">
                                <input type="text" name="user_name" class="w-1/2 rounded-md mr-4" placeholder="Please search User Name">
                                <button type="submit" class="block text-white px-4 bg-green-500 py-2 font-semi-bold hover: border-green-500 hover:bg-green-600 transition duration-300 rounded-md">Search</button>
                            </div>
                        </form>

                        @foreach ($all_users as $user)
                            @if (Auth::User()->id != $user->id)
                                <div class="flex items-center justify-between bg-white rounded-lg shadow p-4 mb-4 hover:bg-gray-50 transition">
                                    <a href="{{ route('profile.show', ['user_id' => $user->id]) }}" class="flex items-center space-x-4 w-full ml-2">
                                        @if ($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover">
                                        @else
                                            <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="w-12 h-12 rounded-full object-cover">
                                        @endif

                                        <div class="text-center">
                                            <p class="font-semibold text-2xl truncate">{{ $user->name }}</p>
                                        </div>
                                    </a>
                                    @if (Auth::id() !== $user->id)
                                        <div class="mt-4">
                                            @if ($user->isFollowed())
                                                <div class="flex  gap-2">
                                                    <form action="{{ route('profile.follow.delete', $user->id) }}" method="post">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="bg-gray-500 text-white px-4 py-1 rounded-md hover:bg-gray-600">
                                                            Following
                                                        </button>
                                                    </form>
                                                    @if (($user->private_group->isNotEmpty() && $user->private_group->contains('name', Auth::User()->name)))
                                                        <a href="{{ route('message.show', $user->private_group->first()->id) }}" class="block bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600">
                                                            message
                                                        </a>
                                                    @elseif ((Auth::User()->private_group->isNotEmpty() && Auth::User()->private_group->contains('name', $user->name)))
                                                        <a href="{{ route('message.show', Auth::User()->private_group->first()->id) }}" class="block bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600">
                                                            message
                                                        </a>
                                                    @endif
                                                </div>
                                            @else
                                                <form action="{{ route('profile.follow.create', $user->id) }}" method="post">
                                                    @csrf
                                                    <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600">
                                                        Follow
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
