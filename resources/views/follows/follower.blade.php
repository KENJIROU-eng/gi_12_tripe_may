<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Follower List</h1>
                    </div>
                    {{-- contents --}}
                    <div class="mx-auto h-full mt-8">
                        @foreach ($followers as $follower)
                            <div class="flex items-center justify-between bg-white rounded-lg shadow p-4 mb-4 hover:bg-gray-50 transition">
                                <a href="" class="flex items-center space-x-4 w-full ml-2">
                                    @if ($follower->follower->avatar)
                                        <img src="{{ $follower->follower->avatar }}" alt="{{ $follower->follower->name }}" class="w-12 h-12 rounded-full object-cover">
                                    @else
                                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($follower->follower->name, 0, 1)) }}
                                    </div>
                                    @endif

                                    <div class="flex flex-row sm:flex-row sm:items-center sm:justify-center text-center sm:space-x-2 ">
                                        <p class="font-semibold text-2xl truncate ">{{$follower->follower->name}}</p>
                                    </div>
                                    {{-- @if ($user->isFollowed())
                                        <div class="text-center text-white mr-4">
                                            <form action="{{ route('follow.delete', $user->id) }}" class="bg-gray-500 px-4 rounded-md hover:bg-gray-600" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="">Following</button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="text-center text-white mr-4">
                                            <form action="{{ route('follow.create', $user->id) }}" class="bg-blue-500 px-4 rounded-md hover:bg-blue-600" method="post">
                                                @csrf
                                                <button type="submit" class="">Follow</button>
                                            </form>
                                        </div>
                                    @endif --}}
                                </a>
                            </div>
                        @endforeach
                    </div>
                    {{-- paginate --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
