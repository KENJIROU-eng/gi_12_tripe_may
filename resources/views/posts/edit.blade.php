<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Edit Post</h1>
                        <div class="flex ml-auto items-center">
                            <div class="col-auto bg-gray-500 rounded-full w-12 h-12 ml-4">
                                @if ($post->user->avatar)
                                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="object-cover rounded-full w-12 h-12">
                                @else
                                    <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="object-cover rounded-full w-12 h-12">
                                @endif
                            </div>
                            <a href="#">
                                <div class="col-auto ml-3">{{ $post->user->name }}</div>
                            </a>
                        </div>
                    </div>
                    <div class="h-[2px] w-full bg-gradient-to-r from-green-500 via-lime-500 to-emerald-500 my-4"></div>
                    {{-- contents --}}
                    <div class="mx-auto h-full mt-8 ">
                        <form action="{{ route('post.update', $post->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="w-3/4 mx-auto">

                                {{--title--}}
                                <div class="mb-4">
                                    <label for="title" class="block text-sm font-semibold mb-1">Title</label>
                                    <input type="text" name="title" id="title" value="{{ $post->title }}"
                                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-100">
                                </div>
                                @error('title')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror

                                {{--image--}}
                                <div class="flex gap-4 mb-4">
                                    <div class="mt-2">
                                        <label for="image" class="block text-sm font-semibold mb-2">Image</label>
                                        <img id="image-preview" class="rounded-md" src="{{ $post->image }}" alt="Image Preview" style="min-width: 100px; max-width: 200px; width: auto;">
                                    </div>
                                    <div class="flex flex-col justify-end items-end">
                                        <input type="file" name="image" id="image" class="form-control" aria-describedby="image-info" onchange="previewImage(event)">
                                        <div class="form-text text-gray-500 mt-1" id="image-info">
                                            The acceptable formats are jpeg, jpg, png, and gif only. <br>
                                            Max file size is 1048kb.
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
                                        @if (in_array($category->id, $categoryPost_id))
                                            <input type="checkbox" name="category_name[]" value="{{ $category->id }}" checked>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                            </div>
                                        @else
                                            <input type="checkbox" name="category_name[]" value="{{ $category->id }}">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                            </div>
                                        @endif
                                    </label>
                                    @empty
                                        <p class="text-sm text-gray-500">No Categories</p>
                                    @endforelse
                                </div>
                                @error('category_name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror

                                {{--description--}}
                                <div class="mb-6">
                                    <label for="description" class="block text-sm font-semibold mb-1">Description</label>
                                    <textarea name="description" id="description" rows="5" cols="200"
                                    class="w-full border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-100">{{ $post->description }}</textarea>
                                    @error('description')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{--button--}}
                                <div class="grid grid-cols-4 w-full  gap-2">
                                    <div class="col-span-1 col-start-2 w-full">
                                        <a href="{{ route('post.show', $post->id) }}" class="block text-center w-full border border-gray-400 py-2 text-black rounded-md">Cancel</a>
                                    </div>
                                    <div class="col-span-1 col-start-3 w-full">
                                        <button type="submit"
                                            class="w-full bg-green-500
                                                text-white py-2 rounded-md transition duration-300
                                                hover:bg-gradient-to-r  from-green-500 via-lime-500 to-emerald-500 hover:text-white hover:shadow-lg">
                                            Edit Post
                                        </button>
                                        {{-- <button type="submit" class="text-white w-full bg-green-500 py-2 hover: border-green-500 hover:bg-green-600 transition duration-300 rounded-md">Edit Post</button> --}}
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
