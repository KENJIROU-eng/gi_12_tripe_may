<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Create Post</h1>
                        <div class="flex ml-auto items-center">
                            {{-- <div class="col-auto bg-gray-500 rounded-full w-12 h-12 ml-4">
                                @if ($post->user->avatar)
                                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="object-cover rounded-full w-12 h-12">
                                @else
                                    <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="object-cover rounded-full w-12 h-12">
                                @endif
                            </div>
                            <a href="#">
                                <div class="col-auto ml-3">{{ $post->user->name }}</div>
                            </a> --}}
                        </div>
                    </div>
                    <div class="h-[2px] w-full bg-gradient-to-r from-yellow-400 via-orange-400 to-pink-400 my-4"></div>
                    {{-- contents --}}
                    <div class="mx-auto h-full mt-8 ">
                        <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="w-3/4 mx-auto">

                                {{--title--}}
                                <div class="mb-4">
                                    <label for="title" class="block text-sm font-semibold mb-1">Title</label>
                                    <input type="text" name="title" id="title" placeholder="Post title"
                                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:ring-orange-400 focus:border-orange-400">
                                </div>
                                @error('title')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror

                                {{--image--}}
                                <div class="flex gap-4 mb-4">
                                    <div class="mt-2">
                                        <label for="image" class="block text-sm font-semibold mb-2">Image</label>
                                        <img id="image-preview" class="rounded-md  hidden max-h-[200px]" src="" alt="Image Preview" style="min-width: 100px; max-width: 300px; width: auto;">
                                    </div>
                                    <div class="flex flex-col justify-end items-end">
                                        <input type="file" name="image" id="image" class="form-control" aria-describedby="image-info" onchange="previewImage(event)">
                                        <div class="form-text text-gray-500 mt-1" id="image-info">
                                            The acceptable formats are jpeg, jpg, png, and gif only. <br>
                                            Max file size is 2096kb.
                                        </div>
                                    </div>
                                </div>
                                @error('image')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror

                                {{--category--}}
                                <label  class="block text-sm font-semibold mb-1" for="category_name">Category</label>
                                <div class="max-h-20 space-y-2 mb-4 overflow-y-auto p-2 rounded">
                                    @forelse ($all_categories as $category)
                                        <label class="flex items-center space-x-3 cursor-pointer" for="category_name">
                                            <input type="checkbox" name="category_name[]" value="{{ $category->id }}" class="accent-orange-400">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-500">No Categories</p>
                                    @endforelse
                                </div>
                                @error('category_name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror

                                {{--description--}}
                                <div x-data="{ count: 0, max: 500 }" class="mb-6">
                                    <label for="description" class="block text-sm font-semibold mb-1">Description</label>
                                    <textarea name="description" id="description" rows="5" cols="200"  maxlength="500" x-model="$el.value" @input="count = $event.target.value.length"
                                    class="w-full border border-gray-300 rounded focus:outline-none focus:ring focus:ring-orange-400 focus:border-orange-400"></textarea>
                                    <div class="text-right text-sm mt-1" :class="{ 'text-red-500': count >= max, 'text-gray-500': count < max }">
                                            <span x-text="count"></span>/<span x-text="max"></span>
                                    </div>
                                    @error('description')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{--button--}}
                                <div class="grid grid-cols-4 w-full  gap-2">
                                    <div class="col-span-1 col-start-2 w-full py-2 border border-gray-300 rounded-md hover:bg-gray-300 duration-300">
                                        <a href="{{ route('post.list') }}" class="block text-center" >Cancel</a>
                                    </div>
                                    <div class="col-span-1 col-start-3 w-full">
                                        <button type="submit"
                                            class="w-full bg-yellow-300
                                                text-black font-semibold py-2 rounded-md transition duration-300
                                                hover:bg-gradient-to-r from-yellow-400 via-orange-400 to-pink-400 hover:text-white hover:shadow-lg">
                                            Create Post
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{--image preview--}}
                        <script src="{{ asset('js/previewImage.js') }}"></script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
