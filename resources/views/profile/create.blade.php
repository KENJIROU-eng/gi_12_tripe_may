<x-app-layout>
    <div class="py-8 min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="p-6 text-black dark:text-gray-100">

                    {{-- タイトル --}}
                    <div class="text-center mb-6">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold">
                            <span class="text-green-500">{{ $user->name }}</span>'s Profile
                        </h1>
                        <hr class="mt-3 border-green-500">
                    </div>

                    {{-- フォーム --}}
                    <form action="{{ route('profile.create') }}" method="post" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        {{-- Username --}}
                        <div>
                            <label for="name" class="block text-md font-medium mb-1">Username:</label>
                            <input type="text" name="name" id="name" value="{{ $user->name }}"
                                class="w-full rounded-md border border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-md font-medium mb-1">Email:</label>
                            <input type="email" name="email" id="email" value="{{ $user->email }}"
                                class="w-full rounded-md border border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Introduction --}}
                        <div>
                            <label for="introduction" class="block text-md font-medium mb-1">Introduction:</label>
                            <textarea name="introduction" id="introduction" rows="4"
                                class="w-full rounded-md border border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2"
                                placeholder="Tell us about yourself..."></textarea>
                            @error('introduction')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Image Upload --}}
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-center">
                            {{-- プレビュー --}}
                            <div class="sm:col-span-1 flex flex-col items-center">
                                <label for="image" class="block text-md font-medium mb-2">Image:</label>
                                <img id="imagePreview" src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}"
                                    alt="default avatar"
                                    class="w-24 h-24 rounded-full object-cover aspect-square shadow-md">
                            </div>

                            {{-- ファイル選択 --}}
                            <div class="sm:col-span-3">
                                <p class="text-sm text-gray-500 mb-2">
                                    Acceptable formats: jpeg, jpg, png, gif.<br>
                                    Max file size: 1048kb.
                                </p>
                                <input type="file" name="image" id="image" accept="image/*"
                                    onchange="previewImage(event)"
                                    class="block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0 file:text-sm file:font-semibold
                                           file:bg-green-500 file:text-white hover:file:bg-green-600">
                                @error('image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="text-center pt-4">
                            <button type="submit"
                                class="px-6 py-2 bg-green-500 text-white text-lg rounded-md hover:bg-green-600 transition">
                                Submit
                            </button>
                        </div>
                    </form>

                    {{-- プレビューJS --}}
                    <script>
                        function previewImage(event) {
                            const reader = new FileReader();
                            reader.onload = function () {
                                const output = document.getElementById('imagePreview');
                                output.src = reader.result;
                            };
                            reader.readAsDataURL(event.target.files[0]);
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- image exhibition js --}}
<script src="{{ asset('js/image_quick-exhibition.js') }}"></script>
