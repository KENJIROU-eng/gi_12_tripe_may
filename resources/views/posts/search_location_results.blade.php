<x-app-layout>
    <div class="mt-5 hide-scrollbar">
        <div class="w-11/12 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-screen">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-4 text-black dark:text-gray-100 h-full">

                    {{-- タイトル --}}
                    <div class="relative flex items-center justify-center h-8 my-2 lg:mb-3">
                        <h1 class="text-2xl lg:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">
                            Search Results
                        </h1>
                        <a href="{{ route('post.list') }}"
                           class="ml-auto sm:mr-3 text-xs sm:text-xl text-teal-500 hover:text-teal-700 bg-white border border-teal-500 hover:border-teal-700 rounded-3xl shadow-md p-1 sm:p-2">
                            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Post List
                        </a>
                    </div>

                    <div class="mx-auto mt-6 mb-10 flex flex-col md:flex-row items-end justify-center gap-6">

                        {{-- カテゴリ検索フォーム --}}
                        <form action="{{ route('post.search') }}" method="GET" class="flex flex-col gap-2">
                            <label class="text-sm font-semibold">Category</label>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <select name="search" class="border border-gray-300 rounded px-4 py-2 w-60">
                                    <option value="" disabled selected>Search Category</option>
                                    @foreach ($all_categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                        class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-700 transition">
                                    Search
                                </button>
                            </div>
                        </form>

                        {{-- 位置検索フォーム --}}
                        <form id="searchForm" action="{{ route('post.search.location') }}" method="GET" class="flex flex-col gap-2">
                            <div class="flex flex-col sm:flex-row gap-4 flex-wrap items-end">
                                {{-- Address --}}
                                <div class="flex flex-col">
                                    <label for="address" class="text-sm font-semibold mb-1">Location</label>
                                    <input type="text" id="address" name="address" value="{{ $address }}" placeholder="Enter city or area" class="border p-2 rounded w-60" required>
                                </div>

                                {{-- Radius --}}
                                <div class="flex flex-col">
                                    <label for="radius" class="text-sm font-semibold mb-1">Radius (km)</label>
                                    <input type="number" name="radius" id="radius" value="{{ $radius ?? 20 }}" min="1" class="border p-2 rounded w-32" placeholder="e.g. 20">
                                </div>

                                {{-- Hidden Coordinates --}}
                                <input type="hidden" name="latitude" id="latitude" value="{{ request('latitude') }}">
                                <input type="hidden" name="longitude" id="longitude" value="{{ request('longitude') }}">

                                {{-- Submit --}}
                                <div class="flex flex-col">
                                    <label class="invisible">Submit</label>
                                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                                        Nearby
                                    </button>
                                </div>
                            </div>
                        </form>


                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const form = document.getElementById('searchForm');
                            if (!form) return;

                            form.addEventListener('submit', async (e) => {
                                e.preventDefault();

                                const address = document.getElementById('address').value;
                                const apiKey = '{{ config("services.google_maps.key") }}';

                                try {
                                    const response = await fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${apiKey}`);
                                    const data = await response.json();

                                    if (data.status === 'OK') {
                                        const result = data.results[0];
                                        const location = result.geometry.location;
                                        const types = result.types;

                                        document.getElementById('latitude').value = location.lat;
                                        document.getElementById('longitude').value = location.lng;

                                        const radiusInput = document.getElementById('radius');

                                        // 国名だったら最低1000kmにする（ただし、すでに1000以上ならそのまま）
                                        if (types.includes('country')) {
                                            const currentRadius = parseInt(radiusInput.value, 10) || 0;
                                            if (currentRadius < 1000) {
                                                radiusInput.value = 1000;
                                            }
                                        }

                                        form.submit();
                                    } else {
                                        alert('Location not found. Try a more specific city or area.');
                                    }
                                } catch (error) {
                                    alert('Failed to fetch location.');
                                    console.error(error);
                                }
                            });

                            initMasonry(); // Masonry init after load
                        });
                    </script>

                    {{-- 結果タイトル --}}
                    <div class="mb-2 text-sm text-gray-600">
                        Showing posts near <span class="font-semibold">"{{ $address ?? 'Unknown' }}"</span>
                        within <span class="font-semibold">{{ $radius }} km</span>
                    </div>

                    {{-- 検索結果表示 --}}
                    <div class="max-h-[720px] overflow-auto pb-4">
                        <div class="flex flex-wrap -mx-2" id="post-container">
                            <div class="post-sizer w-full sm:w-1/2 lg:w-1/3"></div>

                            @forelse ($posts as $post)
                                @if ($post->user)
                                    <div class="post-item w-full sm:w-1/2 lg:w-1/3 px-2 mb-4 transition transform hover:scale-[1.03] hover:-translate-y-1">
                                        <div class="relative group bg-white dark:bg-gray-700 shadow-xl overflow-hidden">
                                            <a href="{{ route('post.show', $post->id) }}">
                                                <img src="{{ $post->image }}" alt="{{ $post->title }}" class="w-full h-auto object-cover max-h-[480px]">
                                            </a>
                                            <div class="absolute inset-0 bg-black bg-opacity-30 flex flex-col justify-end opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                                <p class="text-white font-extralight text-xs text-center mt-1">{{ $post->user->name }}</p>
                                                <div class="flex items-center justify-between px-3 pb-3 pointer-events-auto">
                                                    <a href="{{ route('profile.show', $post->user->id) }}" class="ml-2">
                                                        <img src="{{ $post->user->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}"
                                                             class="object-cover rounded-full w-12 h-12 border border-white shadow-lg hover:scale-110 transition">
                                                    </a>
                                                    <h2 class="text-md sm:text-xl font-bold truncate max-w-[150px] text-white">{{ Str::limit($post->title, 12) }}</h2>
                                                    <div wire:ignore>
                                                        @livewire('post-like', ['post' => $post, 'viewType' => 'list'], key($post->id))
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="w-full text-center py-8 text-gray-500">
                                    No posts found in this area.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- 必要なスクリプト --}}
    <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script>
        function initMasonry() {
            const container = document.querySelector('#post-container');
            if (!container) return;

            if (window.masonryInstance) {
                window.masonryInstance.destroy();
            }

            imagesLoaded(container, function () {
                window.masonryInstance = new Masonry(container, {
                    itemSelector: '.post-item',
                    columnWidth: '.post-sizer',
                    percentPosition: true
                });
            });
        }

        document.addEventListener('DOMContentLoaded', initMasonry);
        document.addEventListener('livewire:load', () => {
            Livewire.hook('message.processed', initMasonry);
        });
    </script>
</x-app-layout>
