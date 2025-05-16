@extends('layouts.app')

@section('title','Post Edit')

@section('content')

<div class= "mt-5 h-[880px]">
    <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full bg-gray-200">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
            <div class="p-6 text-black dark:text-gray-100">
                {{-- title --}}
                <div class="relative flex items-center justify-center h-16 my-5">
                    <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Edit Post</h1>
                    <div class="flex ml-auto items-center">
                        <div class="col-auto bg-gray-500 rounded-full w-12 h-12 ml-4"></div>
                        <a href="#">
                            <div class="col-auto ml-3">username</div>
                        </a>
                    </div>
                </div>
                <hr class="border-t border-green-300 w-full my-4">
                {{-- contents --}}
                <div class="mx-auto h-full mt-8 ">
                    <form action="#" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="w-3/4 mx-auto">

                            {{--title--}}
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-semibold mb-1">Title</label>
                                <input type="text" name="title" id="title" value="{{ old('title')}}"
                                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-100"
                                placeholder="Enter post title">
                            </div>

                            {{--image--}}
                            <div class="flex items-end gap-4 mb-4">
                                <div class="pt-2 w-20">
                                    <label for="image" class="block text-sm font-semibold">Image</label>
                                </div>
                                <div class="mt-2">
                                    <img id="image-preview" class="rounded-md hidden" src="{{ old( $post->image ?? '')}}" alt="Image Preview" style="min-width: 100px; max-width: 300px; width: auto;">
                                </div>
                                <div class="flex flex-col justify-end">
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
                            <div class="mb-4">
                                <label for="category" class="block text-sm font-semibold mb-1">Category</label>
                                <select name="category" id="category"
                                class="w-full border border-gray-300 rounded px-4 py-2 bg-white focus:outline-none focus:ring focus:border-blue-100">
                                <option value="">Select a category</option>
                                {{--old('category')--}}
                                </select>
                                @error('category')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{--description--}}
                            <div class="mb-6">
                                <label for="description" class="block text-sm font-semibold mb-1">Description</label>
                                <textarea name="description" id="description" rows="5"
                                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-100"
                                placeholder="Write your post content...">{{ old('description') }}
                                </textarea>
                                @error('description')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{--button--}}
                            <div class="flex justify-between">
                                {{--cannot change color, Need to confirm--}}
                                <button class="border border-gray-400 px-6 py-2 text-black rounded-md">Cancel</button>
                                <button type="submit" class="bg-yellow-200 px-6 py-2 text-black hover: border-yellow-200 hover:bg-white transition duration-300 rounded-md">Edit Post</button>
                            </div>
                        </div>
                    </form>
                    {{--image preview--}}
                    <script>
                        function previewImage(event) {
                            const input = event.target;
                            const preview = document.getElementById('image-preview');

                            if (input.files && input.files[0]) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    preview.src = e.target.result;
                                    preview.classList.remove('hidden');
                                };
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection