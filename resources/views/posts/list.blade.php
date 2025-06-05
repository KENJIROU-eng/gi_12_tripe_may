<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100 h-full">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Post List</h1>
                        <a href="{{ route('post.create') }}" class="absolute right-40 no-underline text-end ">
                            <i class="fa-solid fa-plus ml-auto"></i> add Post
                        </a>
                    </div>
                    {{-- contents --}}
                    <div class="mx-auto h-4/5 mt-4 overflow-hidden">
                        <form action="{{ route('post.search') }}" method="get" class="w-full">
                            <div class="flex justify-center items-center mb-3 w-full">
                                <select name="search" class="block border border-gray-300 rounded w-2/3 focus:ring-2 me-3">
                                    <option value="" selected disabled>Search Category</option>
                                    @foreach ($all_categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="block text-white px-4 bg-green-500 py-2 font-semi-bold hover: border-green-500 hover:bg-green-600 transition duration-300 rounded-md">Search</button>
                            </div>
                        </form>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-14 mx-auto">
                        @foreach ($all_posts as $post)
                            <div x-data="{ showModal: false }" class="w-11/12 transform transition duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer relative">
                                <a href="{{ route('post.show', $post->id) }}">
                                    <img src="{{ $post->image }}" alt="{{ $post->title }}" class="object-cover p-4 rounded shadow w-auto h-52">
                                </a>
                                <div class="flex p-3 items-center">
                                    @if ($post->user->avatar)
                                        <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="object-cover rounded-full w-12 h-12">
                                    @else
                                        <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="object-cover rounded-full w-12 h-12">
                                    @endif
                                    <h2 class="text-xl font-bold pl-4">{{ $post->title }}</h2>
                                    <i class="fa-regular fa-trash-can ml-auto hover:text-red-500 cursor-pointer"
                                        @click="showModal = true"></i>
                                </div>
                                @include('posts.modals.delete')
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
