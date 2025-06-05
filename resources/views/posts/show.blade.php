<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100 h-full">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Post List</h1>
                    </div>
                    {{-- contents --}}
                    <div class="mx-auto h-4/5 mt-8 overflow-hidden">
                        <div class="flex justify-center h-full">
                            <div class="w-3/4 bg-white bg-opacity-90 shadow-lg p-10 px-16 rounded h-full mt-3 mx-auto sm:px-6 lg:px-8">
                
                                {{--contents--}}
                                <div x-data="{ showModal: false }" class="container shadow-lg w-3/5 h-full border mx-auto mt-8">
                                    <div class="row items-center flex mt-4">
                                        <div class="col-auto bg-gray-600 rounded-full w-14 h-14 ml-4">
                                            @if ($post->user->avatar)
                                                <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="object-cover rounded-full w-14 h-14">
                                            @else
                                                <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="object-cover rounded-full w-14 h-14">
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
                                                    @endif
                                                    <button @click="showModal = true" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                        Delete
                                                    </button>
                                                </x-slot>
                                            </x-dropdown>
                                        </div>
                                    </div>

                                    {{--title--}}
                                    <h1 class="font-bold text-center mb-2">{{ $post->title }}</h1>

                                    {{--image--}}
                                    <div class="w-100 h-1/2">
                                        <img src="{{ $post->image }}" alt="{{ $post->title }}" class="object-cover p-4 rounded shadow w-full h-full">
                                    </div>
                                    {{--likes,category--}}
                                    <div class="row flex items-center mt-2">
                                        <div class="col-auto mx-4"><i class="fa-regular fa-heart text-red-600 text-3xl"></i></div>
                                        <div class="col-auto ">{{ $post->likes()->count() }}</div>
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
    </div>
</x-app-layout>
