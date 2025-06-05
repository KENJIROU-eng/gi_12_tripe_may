<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100 h-full">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2"><span class="text-green-500">{{ $user->name }}</span>`s Profile</h1>
                    </div>
                    <hr class="border-green-500 border-1">
                    {{-- contents --}}
                    <div class="mx-auto h-4/5 mt-8 overflow-hidden">
                        {{-- body --}}
                        <form action="{{ route('profile.create') }}" class="mx-auto w-full mt-3" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="grid grid-cols-6 gap-2">
                                <div class="col-span-4 col-start-2 mb-2 me-auto w-full">
                                    <label for="name" class="block text-md font-medium text-gray-900 mb-2">Username:</label>
                                    <input type="text" name="name" id="name" class="block rounded-md w-full mt-2" value="{{ $user->name }}">
                                    @error('name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-span-4 col-start-2 mb-2 me-auto w-full">
                                    <label for="email" class="block text-md font-medium text-gray-900 mb-2">Email:</label>
                                    <input type="text" name="email" id="email" class="block rounded-md w-full mt-2" value="{{ $user->email }}">
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-span-4 col-start-2 mb-2 me-auto w-full">
                                    <label for="introduction" class="block text-md font-medium text-gray-900 mb-2">Introduction:</label>
                                    <textarea name="introduction" id="introduction" cols="30" rows="5" class="block rounded-md w-full mt-2" placeholder="introduction"></textarea>
                                    @error('introduction')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-span-4 col-start-2 mb-2 me-auto w-full h-40">
                                    <div class="grid grid-cols-4 gap-4">
                                        <div class="ms-2 mt-2 col-span-1 w-full h-40 flex flex-col">
                                            <label for="image" class="block text-md font-medium text-gray-900">Image:</label>
                                            <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="h-2/3 aspect-square mx-auto mt-auto object-cover" id="imagePreview">
                                        </div>
                                        <div class="col-span-3 w-full h-40 me-auto flex flex-col">
                                            <div class="form-text text-gray-500 mt-auto" id="image-info">
                                                <p class="mb-2">
                                                    The acceptable formats are jpeg, jpg, png, and gif only. <br>
                                                    Max file size is 1048kb.
                                                </p>
                                                <input type="file" name="image" id="image" class="form-control" aria-describedby="image-info" onchange="previewImage(event)" accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-2 col-start-3 mt-3">
                                    <button type="submit" class="w-full bg-green-500 font-semi-bold rounded text-white py-2 text-xl hover:bg-green-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                                        Enter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- image exhibition js --}}
<script src="{{ asset('js/image_quick-exhibition.js') }}"></script>
