<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full w-full">
                <div class="p-6 text-black dark:text-gray-100 h-full w-full">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-3 w-full">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2"><span class="text-green-500">{{ $user->name }}</span>`s Profile</h1>
                        @if (Auth::User()->id === $user->id)
                            <a href="{{ route('profile.edit') }}" class="absolute right-40">
                                <i class="fa-solid fa-user-pen"></i> Profile Edit
                            </a>
                        @endif
                    </div>
                    <hr class="border-green-500 border-1">
                    {{-- contents --}}
                    <div class="mx-auto h-4/5 mt-6 overflow-hidden w-full">
                        {{-- body --}}
                        <div class="grid grid-cols-6 h-1/5">
                            <div class="flex flex-col col-span-2 h-full w-full">
                                <div class="relative w-1/3 aspect-square bg-red-400 mx-auto my-auto rounded-full overflow-hidden">
                                    @if ($user->avatar)
                                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="absolute inset-0 w-full h-full object-cover">
                                    @else
                                    <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="absolute inset-0 w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex justify-between mx-auto mt-2 w-2/3">
                                    <div>
                                        <div class="text-decoration-none text-dark">
                                            <strong>{{ $user->post()->count() }}</strong> {{ $user->post()->count() <= 1 ? 'Post':'Posts' }}
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ route('follower.show', $user->id) }}" class="text-decoration-none text-dark hover:text-blue-500">
                                            <strong>{{ $user->followers->count() }}</strong> {{ $user->followers->count() <= 1 ? 'Follower':'Followers' }}
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('following.show', $user->id) }}" class="text-decoration-none text-dark hover:text-blue-500">
                                            <strong>{{ $user->following->count() }}</strong> Following
                                        </a>
                                    </div>
                                    @if (Auth::User()->id != $user->id)
                                        @if ($user->isFollowed())
                                            <div class="text-center text-white">
                                                <form action="{{ route('follow.delete', $user->id) }}" class="bg-gray-500 px-4 rounded-md hover:bg-gray-600" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="">Following</button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="text-center text-white">
                                                <form action="{{ route('follow.create', $user->id) }}" class="bg-blue-500 px-4 rounded-md hover:bg-blue-600" method="post">
                                                    @csrf
                                                    <button type="submit" class="">Follow</button>
                                                </form>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-col col-span-4 h-full w-full">
                                <div class="grid grid-cols-5 h-1/3 w-full ms-2 mb-1">
                                    <div class="col-span-1">
                                        <h3 class="text-md sm:text-xl">Username:</h3>
                                    </div>
                                    <div class="col-span-4">
                                        <h3 class="text-md sm:text-2xl ms-2">
                                            {{ $user->name }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="grid grid-cols-5 h-2/3 w-full ms-2">
                                    <div class="col-span-1">
                                        <h3 class="text-md sm:text-xl">Introduction:</h3>
                                    </div>
                                    <div class="col-span-4 bg-gray-50">
                                        <h3 class="text-md sm:text-xl ms-2">
                                            {{ $user->introduction }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 w-2/3 mt-10 mx-auto">
                            @foreach ($all_posts as $post)
                            <div class="col-span-1 w-full aspect-square bg-blue-500 flex justify-center items-center border border-green-100 border-1">
                                <a href="{{ route('post.show', $post->id) }}" class="hover:scale-105">
                                    <img src="{{ $post->image }}" alt="{{ $post->title }}" class="w-full aspect-square object-cover">
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    {{-- paginate --}}
                    <div class="flex justify-center">
                        {{ $all_posts->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

