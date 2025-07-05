<x-app-layout>
    <div class="py-8 min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="p-6 text-black dark:text-gray-100">

                    {{-- タイトル --}}
                    <div class="text-center mb-6">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold">
                            <span class="text-green-500">{{ $user->name }}</span>'s Profile
                        </h1>
                        <hr class="mt-3 border-green-500">
                    </div>

                    {{-- フォーム --}}
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        {{-- Username --}}
                        <div>
                            <label for="name" class="block text-md font-medium mb-1">Username:</label>
                            <input type="text" name="name" id="name" value="{{ $user->name }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-md font-medium mb-1">Email:</label>
                            <input type="email" name="email" id="email" value="{{ $user->email }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Introduction --}}
                        <div>
                            <label for="introduction" class="block text-md font-medium mb-1">Introduction:</label>
                            <textarea name="introduction" id="introduction" rows="4"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 p-2"
                            >{{ old('introduction', $user->introduction) }}</textarea>
                            @error('introduction')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Image --}}
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-center">
                            <div class="sm:col-span-1 flex flex-col items-center">
                                <label for="image" class="block text-md font-medium mb-2">Image:</label>
                                <img id="imagePreview" src="{{ $user->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}"
                                    alt="{{ $user->name }}"
                                    class="w-24 h-24 rounded-full object-cover aspect-square shadow-md">
                            </div>
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

                        {{-- 公開範囲設定 --}}
                        <div>
                            <label class="block text-md font-medium mb-1">Search Visibility:</label>
                            <div class="flex items-center gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="is_public" value="1"
                                        {{ old('is_public', $user->is_public) ? 'checked' : '' }}
                                        class="text-green-500 focus:ring-green-500">
                                    <span class="ml-2">Visible</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="is_public" value="0"
                                        {{ old('is_public', $user->is_public) ? '' : 'checked' }}
                                        class="text-green-500 focus:ring-green-500">
                                    <span class="ml-2">Hidden</span>
                                </label>
                            </div>
                            @error('is_public')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ボタン --}}
                        <div class="flex flex-col sm:flex-row sm:justify-between gap-4 mt-4">
                            <a href="{{ route('profile.show', $user->id) }}" class="w-full sm:w-1/2">
                                <button type="button"
                                    class="w-full bg-gray-500 text-white py-2 rounded-md text-lg hover:bg-gray-600 transition">
                                    Cancel
                                </button>
                            </a>
                            <div class="w-full sm:w-1/2">
                                <button type="submit"
                                    class="w-full bg-green-500 text-white py-2 rounded-md text-lg hover:bg-green-600 transition">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- プレビュー用JS --}}
                    <script>
                        function previewImage(event) {
                            const reader = new FileReader();
                            reader.onload = function () {
                                document.getElementById('imagePreview').src = reader.result;
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
