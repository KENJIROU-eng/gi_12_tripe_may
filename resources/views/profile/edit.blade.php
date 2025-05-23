<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100 h-full">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Profile</h1>
                    </div>
                    <hr class="border-green-500 border-1">
                    {{-- contents --}}
                    <div class="mx-auto h-4/5 mt-8 overflow-hidden">
                        {{-- body --}}
                        <form action="" class="mx-auto w-full mt-3">
                            <div class="grid grid-cols-6 gap-2">
                                <div class="col-span-4 col-start-2 mb-2 me-auto w-full">
                                    <label for="name" class="block text-md font-medium text-gray-900 mb-2">Username:</label>
                                    <input type="text" name="name" id="name" class="block rounded-md w-full mt-2" placeholder="name">
                                    @error('name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-span-4 col-start-2 mb-2 me-auto w-full">
                                    <label for="email" class="block text-md font-medium text-gray-900 mb-2">Email:</label>
                                    <input type="text" name="email" id="email" class="block rounded-md w-full mt-2" placeholder="email">
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
                                    <div class="grid grid-cols-3">
                                        <div class="col-span-1 bg-blue-500 w-full h-40">
                                            <label for="image" class="block text-sm font-semibold">Image</label>
                                        </div>
                                        <div class="col-span-1 bg-green-500 w-full h-40">
                                        </div>
                                        <div class="col-span-1 bg-red-500 w-full h-40">
                                        </div>
                                    </div>
                                    {{-- test --}}
                                    
                                    {{-- <div class="flex items-end gap-4 mb-4 bg-green-500">
                                        <div class="pt-2 w-20 bg-red-500">
                                            <label for="image" class="block text-sm font-semibold">Image</label>
                                        </div>
                                        <div class="mt-2 bg-cyan-500">
                                            <img id="image-preview" class="rounded-md hidden" src="{{ old( $post->image ?? '')}}" alt="Image Preview" style="min-width: 100px; max-width: 300px; width: auto;">
                                        </div>
                                        <div class="flex flex-col justify-end bg-blue-500">
                                            <input type="file" name="image" id="image" class="form-control" aria-describedby="image-info" onchange="previewImage(event)">
                                            <div class="form-text text-gray-500 mt-1" id="image-info">
                                                The acceptable formats are jpeg, jpg, png, and gif only. <br>
                                                Max file size is 1048kb.
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="col-span-2 col-start-2 mt-2">
                                    <a href="">
                                        <button type="button" class="w-full bg-gray-500 font-semi-bold text-white py-2 rounded text-xl hover:bg-gray-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-500">
                                            Cancel
                                        </button>
                                    </a>
                                </div>
                                <div class="col-span-2 col-start-4 mt-2">
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

{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
