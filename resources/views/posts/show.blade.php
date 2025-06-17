<x-app-layout>
    <div style="background-image: url('/images/pexels-fotios-photos-5653734.jpg'); background-size: cover; background-position: center;">
        <div class= "mt-5 h-[880px]">
            <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
                <div class="bg-gray-50 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                    <div class="p-6 text-black dark:text-gray-100 h-full">
                        {{-- title --}}
                        {{-- <div class="relative flex items-center justify-center h-16 my-5">
                            <h1 class="text-md sm:text-3xl lg:text-4xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Post List</h1>
                        </div> --}}
                        {{-- contents --}}
                        <div class="mx-auto h-[820px] overflow-hidden">
                            <div class="flex justify-center h-full">

                                    {{--contents--}}
                                    <div x-data="{ showModal: false }" class="container shadow-lg 2xl:w-3/5 w-4/5 h-full border mx-auto">
                                        <div class="row items-center flex mt-4">
                                            <div class="col-auto bg-gray-600 rounded-full w-14 h-14 ml-4">
                                                @if ($post->user->avatar)
                                                    <a href="{{ route('profile.show', $post->user->id) }}">
                                                        <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="object-cover rounded-full w-14 h-14">
                                                    </a>
                                                @else
                                                    <a href="{{ route('profile.show', $post->user->id) }}">
                                                        <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="object-cover rounded-full w-14 h-14">
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="col-auto ml-2">{{ $post->user->name }}</div>
                                            <div class="col ml-auto mr-6">
                                                <x-dropdown align="right" width="46" >
                                                    <x-slot name="trigger">
                                                        <i class="fa-solid fa-ellipsis cursor-pointer"></i>
                                                    </x-slot>
                                                <x-slot name="content">
                                                    @if ($post->user->id == Auth::User()->id)
                                                        <a href="{{ route('post.edit', $post->id) }}" class="block px-4 py-2 text-sm text-green-700 hover:bg-green-100">
                                                            Edit
                                                        </a>
                                                        <button @click="showModal = true" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                            Delete
                                                        </button>
                                                    @endif
                                                </x-slot>
                                            </x-dropdown>
                                        </div>




                                    {{--title--}}
                                    <h1 class="font-bold text-center mb-2">{{ $post->title }}</h1>

                                    {{--image--}}
                                    <div class="w-100 h-1/2">
                                        <img src="{{ $post->image }}" alt="{{ $post->title }}" class="object-cover p-4 rounded shadow w-full h-full">
                                    </div>
                                    {{--likes,category--}}
                                    <div class="row flex items-center mt-2">
                                        <div class="col-auto mx-4">
                                            @if (in_array(Auth::User()->id, $post->likes->pluck('user_id')->toArray()))
                                                <form action="{{ route('post.like.delete', $post->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit">
                                                        <i class="fa-solid fa-heart text-red-500 text-3xl"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('post.like', $post->id) }}">
                                                    <i class="fa-regular fa-heart text-gray-400 hover:text-red-500 text-3xl"></i>
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col-auto ">
                                            {{-- trigger --}}
                                            <div x-data="{ open: false }">
                                                <button @click="open = true">
                                                    {{ $post->likes()->count() }}
                                                </button>
                                                {{-- modal content --}}
                                                @include('posts.modals.likeUser', ['post' => $post])
                                            </div>
                                        </div>
                                        <div class="col text-end text-blue-400 font-light ml-auto mr-4">
                                            @foreach ($post->categoryPost as $categoryPost)
                                                #{{ $categoryPost->category->name }}
                                            @endforeach
                                        </div>
                                    </div>
                                    {{--description--}}
                                    <p class="font-light p-4">{{ $post->description }}</p>
                                    @include('posts.modals.delete', ['post' => $post])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</x-app-layout>
