<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    {{-- title --}}
                    <div class="">
                        <a href="{{ route('profile.show', $user->id) }}" class="inline-flex items-center text-sm sm:text-base text-blue-500 hover:underline">
                            <i class="fa-solid fa-arrow-left mr-1"></i> Back to profile page
                        </a>
                    </div>

                    <!-- 見出し（中央） -->
                    <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold text-center">
                        Follower List
                    </h1>
                    {{-- contents --}}
                    <div class="mx-auto h-full mt-8">
                        @foreach ($followers as $follower)
                            @if (!empty($follower->follower))
                            <div class="flex items-center justify-between bg-white rounded-lg shadow p-4 mb-4 hover:bg-gray-50 transition">
                                <a href="{{ route('profile.show', ['user_id' => $follower->follower->id]) }}" class="flex items-center space-x-4 w-full ml-2">
                                    @if ($follower->follower->avatar)
                                        <img src="{{ $follower->follower->avatar }}" alt="{{ $follower->follower->name }}" class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($follower->follower->name, 0, 1)) }}
                                        </div>
                                    @endif

                                    <div class="text-center">
                                        <p class="font-semibold text-2xl truncate">{{ $follower->follower->name }}</p>
                                    </div>
                                </a>
                            </div>
                            @endif
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
