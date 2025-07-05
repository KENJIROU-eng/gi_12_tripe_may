<x-app-layout>
    {{-- <div style="background-image: url('/images/pexels-fotios-photos-5653734.jpg'); background-size: cover; background-position: center;"> --}}
            {{-- <div class= " fixed inset-0 bg-gray-50 dark:bg-gray-800 -z-10"></div> --}}
            <div class="mt-5 w-11/12 md:w-4/5 mx-auto lg:px-8">
                <div class=" shadow-sm sm:rounded-lg">
                    <div class="pt-4 text-black dark:text-gray-100">
                        <div class="relative flex flex-col items-center">
            {{-- <div class="mt-5 w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 flex-grow">
                <div class=" overflow-hidden shadow-sm sm:rounded-lg h-full flex flex-col">
                    <div class="pt-2 text-black dark:text-gray-100 h-full">
                        <div class="relative flex justify-center items-center flex-col h-full"> --}}
                            <a href="{{ route('post.list') }}" class="text-teal-500 hover:text-teal-700 font-semibold text-xl flex items-center mb-2 md:mb-0 md:absolute md:left-0 md:top-0 md:pl-4">
                                <i class="fa-solid fa-arrow-left mr-2"></i> Post List
                            </a>
                            {{-- <div class="hidden md:block w-[100px]"></div> --}}
                            <div class="container shadow-lg md:w-3/5 w-11/12 max-h-[90vh] overflow-y-auto border mx-auto mb-12">
                                <div class="flex items-center mt-4">
                                    <div class="rounded-full overflow-hidden w-12 h-12 md:w-14 md:h-14 ml-4">
                                        @if ($post->user->avatar)
                                            <a href="{{ route('profile.show', $post->user->id) }}">
                                                <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="object-cover w-14 h-14">
                                            </a>
                                        @else
                                            <a href="{{ route('profile.show', $post->user->id) }}">
                                                <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="object-cover w-14 h-14">
                                            </a>
                                        @endif
                                        </div>
                                        <div class="ml-2">{{ $post->user->name }}</div>
                                        <div x-data="{ showModal: false }" class="ml-auto mr-6">
                                            <x-dropdown align="right" width="46">
                                                <x-slot name="trigger">
                                                    <i class="fa-solid fa-ellipsis cursor-pointer"></i>
                                                </x-slot>
                                                <x-slot name="content">
                                                    @if ($post->user->id == Auth::User()->id)
                                                        <a href="{{ route('post.edit', $post->id) }}"
                                                        class="block px-4 py-2 text-sm text-green-700 hover:bg-green-100">
                                                        Edit
                                                        </a>
                                                        <button @click="showModal = true"
                                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                            Delete
                                                        </button>
                                                    @else
                                                        @if ($post->user->isFollowed())
                                                            <form action="{{ route('profile.follow.delete', $post->user->id) }}" method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button
                                                                    type="submit"
                                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-100">
                                                                    Following
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('profile.follow.create', $post->user->id) }}" method="post">
                                                            @csrf
                                                                <button
                                                                    type="submit"
                                                                    class="block w-full text-left px-4 py-2 text-sm text-blue-600 hover:bg-blue-100">
                                                                    Follow
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </x-slot>
                                            </x-dropdown>
                                            @include('posts.modals.delete', ['post' => $post])
                                        </div>
                                    </div>

                                    <!-- Title -->
                                    <h1 class="font-bold text-center mb-2 text-lg md:text-xl lg:text-2xl">{{ $post->title }}</h1>

                                    <!-- 公開範囲表示 -->
                                    <div class="text-center text-sm text-gray-500 mb-2">
                                    @php
                                        $visibilityLabels = [
                                            'public' => 'Visible to: Everyone',
                                            'self' => 'Visible to: Only Me',
                                            'followers' => 'Visible to: Followers Only',
                                            'groups' => 'Visible to: Specific Groups',
                                            'followers_groups' => 'Visible to: Followers + Groups',
                                            'custom' => 'Visible to: Selected Users',
                                        ];
                                    @endphp
                                        <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">
                                            {{ $visibilityLabels[$post->visibility] ?? 'Visibility: Not set' }}
                                        </span>
                                    </div>
                                    <!-- Image -->
                                    <div class="mb-2 bg-gradient-to-r from-gray-200 via-white to-gray-200 ">
                                        <img src="{{ $post->image }}" alt="{{ $post->title }}" class="object-cover mx-auto max-h-[340px] sm:max-h-[400px] md:max-h-[500px] lg:max-h-[560px] shadow">
                                    </div>
                                    <!-- Likes, Categories -->
                                    <div class="flex items-center">
                                        {{-- <div class="ml-4">
                                            @if (in_array(Auth::User()->id, $post->likes->pluck('user_id')->toArray()) )
                                                <form action="{{ route('post.like.delete',$post->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit">
                                                        <i class="fa-solid fa-heart text-red-500 hover:text-gray-600 text-2xl mr-2"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('post.like',$post->id) }}">
                                                    <i class="fa-regular fa-heart text-gray-400 hover:text-red-500 text-2xl mr-2"></i>
                                                </a>
                                            @endif
                                        </div> --}}
                                        {{-- @livewire('post-like', ['post' => $post], key($post->id)) --}}
                                        @livewire('post-like', ['post' => $post, 'viewType' => 'show'], key($post->id))
                                        {{-- <div x-data="{open:false}" class="mr-4">
                                            <button @click="open = true">
                                                {{ $post->likes()->count() }}
                                            </button>
                                            @include('posts.modals.likeUser', ['post' => $post])
                                        </div> --}}
                                        <div class="col-auto">
                                            {{-- trigger --}}
                                            <div x-data="{ open: false }">
                                                <div class="flex items-center space-x-1">
                                                    <button @click="open = true" class="text-gray-400 hover:text-gray-600 text-2xl ml-1">
                                                        <i class="fa-regular fa-comments"></i>
                                                    </button>
                                                    <p class="text-black">{{ $post->comments->count() }}</p>
                                                </div>
                                                {{-- modal content --}}
                                                @include('posts.modals.comments', ['post' => $post])
                                            </div>
                                        </div>
                                        <div class="ml-auto text-blue-400 mr-3">
                                            @foreach ($post->categoryPost as $categoryPost)
                                            #{{ $categoryPost->category->name }}
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500 ml-3 mt-1">
                                        {{ $post->created_at->format('M d, Y') }}
                                    </div>

                                    {{-- 選択された Itinerary と目的地 --}}
                                    @if ($post->itinerary)
                                        <div class="my-1 px-4">
                                            <h2 class="text-lg font-semibold text-teal-600 mb-1">
                                                Itinerary: {{ $post->itinerary->title }}
                                            </h2>

                                            @if ($post->mapItineraries->isNotEmpty())
                                                <ul class="list-disc list-inside text-sm text-gray-700">
                                                    @foreach ($post->mapItineraries as $map)
                                                        <li>{{ $map->place_name ?? $map->destination }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-sm text-gray-500">No destinations selected.</p>
                                            @endif
                                        </div>
                                    @endif
                                        <div class="px-4 mb-2">
                                            <div class="font-light whitespace-pre-line break-words text-gray-800 dark:text-gray-100">
                                                {{ $post->description }}
                                            </div>
                                        </div>
                                    {{-- @endif --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- </div> --}}
</x-app-layout>
















