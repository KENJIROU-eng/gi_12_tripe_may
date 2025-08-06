<x-app-layout >
    <div class="mt-5  hide-scrollbar {{ Route::currentRouteName() == 'post.list' ? 'relative z-10' : '' }}">
        <div class="w-11/12 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-screen">
            <div class=" bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-4 text-black dark:text-gray-100 h-full">

                    {{-- タイトル --}}
                    <div class="relative flex items-center justify-center h-8 my-2 lg:mb-3">
                        <h1 class="text-2xl  lg:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Post List</h1>
                        <a href="{{ route('post.create') }}" class="ml-auto  sm:mr-3 right-40 no-underline text-end text-xs sm:text-xl text-teal-500 hover:text-teal-700 bg-white border border-teal-500 hover:border-teal-700 rounded-3xl shadow-md p-1 sm:p-2">
                            <i class="fa-solid fa-plus ml-auto"></i> Add Post
                        </a>
                    </div>

                    <div class="mx-auto mt-6 mb-10 flex flex-col md:flex-row items-end justify-center gap-8">

                        {{-- カテゴリ検索フォーム --}}
                        <form action="{{ route('post.search') }}" method="GET" class="flex flex-col gap-2">
                            <label class="text-sm font-semibold">Category</label>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <select name="search" class="border border-gray-300 rounded px-4 py-2 w-60">
                                    <option value="" selected disabled>Search Category</option>
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
                            <div class="flex flex-col sm:flex-row gap-2 flex-wrap">
                                {{-- Address --}}
                                <div class="flex flex-col">
                                    <label for="address" class="text-sm font-semibold mb-1">Location</label>
                                    <input type="text" id="address" name="address" placeholder="Enter city or area" class="border p-2 rounded w-60" required>
                                </div>

                                {{-- Radius --}}
                                <div class="flex flex-col">
                                    <label for="radius" class="text-sm font-semibold mb-1">Radius (km)</label>
                                    <input type="number" name="radius" id="radius" value="20" min="1" class="border p-2 rounded w-32" placeholder="e.g. 20">
                                </div>

                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">

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
                            form.addEventListener('submit', async (e) => {
                                e.preventDefault(); // 一旦止める

                                const address = document.getElementById('address').value;
                                const apiKey = '{{ config("services.google_maps.key") }}';

                                try {
                                    const response = await fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${apiKey}`);
                                    const data = await response.json();

                                    if (data.status === 'OK') {
                                        const result = data.results[0]; // ← ここ
                                        const location = result.geometry.location;
                                        document.getElementById('latitude').value = location.lat;
                                        document.getElementById('longitude').value = location.lng;

                                        const types = result.types; // ← ここ

                                        const radiusInput = document.getElementById('radius');
                                        const currentRadius = parseInt(radiusInput.value, 10) || 0;
                                        if (types.includes('country') && currentRadius < 1000) {
                                            radiusInput.value = 1000;
                                        }

                                        form.submit(); // 緯度経度がセットされたら送信
                                    } else {
                                        alert('Location not found. Try being more specific (e.g. Cebu City, Tokyo, etc).');
                                    }
                                } catch (err) {
                                    alert('Failed to fetch geolocation. Please try again later.');
                                    console.error(err);
                                }
                            });
                        });
                    </script>

                    {{-- 投稿リスト --}}
                    <div id="scroll-container" class="max-h-[780px] overflow-auto pb-4">
                        <div id="post-container" class="flex flex-wrap -mx-2" wire:ignore>

                            {{-- Masonry用のsizer（ブレイクポイントで幅制御） --}}
                            <div class="post-sizer w-full sm:w-1/2 lg:w-1/3"></div>

                            @foreach ($all_posts as $post)
                                @if ($post->user)
                                <div class="post-item w-full sm:w-1/2 lg:w-1/3 px-2 mb-4 transition transform hover:scale-[1.03] hover:-translate-y-1">
                                    <div class="relative group bg-white dark:bg-gray-700 shadow-xl overflow-hidden">
                                        <a href="{{ route('post.show', $post->id) }}">
                                            <img src="{{ $post->image }}" alt="{{ $post->title }}" class="w-full h-auto object-cover max-h-[480px]">
                                        </a>

                                        {{-- オーバーレイ --}}
                                        <div class="absolute inset-0 bg-black bg-opacity-30 flex flex-col justify-end opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                            <p class="text-white font-extralight text-xs text-center mt-1">{{ $post->user->name }}</p>
                                            <div class="flex items-center justify-between px-3 pb-3 pointer-events-auto">
                                                <a href="{{ route('profile.show', $post->user->id) }}" class="ml-2">
                                                    @if ($post->user->avatar)
                                                        <img src="{{ $post->user->avatar }}" class="object-cover rounded-full w-12 h-12 hover:scale-110 transition border border-white shadow-lg">
                                                    @else
                                                        <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" class="object-cover rounded-full w-12 h-12 hover:scale-110 transition">
                                                    @endif
                                                </a>
                                                <h2 class="text-md sm:text-xl font-bold text-center truncate max-w-[150px] text-white">{{ Str::limit($post->title, 12) }}</h2>

                                                {{-- Likeボタン wire:ignoreでDOM干渉防止 --}}
                                                <div wire:ignore>
                                                    {{-- @livewire('post-like', ['post' => $post], key($post->id)) --}}
                                                    @livewire('post-like', ['post' => $post, 'viewType' => 'list'], key($post->id))
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- スクリプト --}}
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

        function safeMasonry() {
            initMasonry();
            setTimeout(initMasonry, 300);
        }

        document.addEventListener('DOMContentLoaded', safeMasonry);
        document.addEventListener('livewire:load', () => {
            safeMasonry();
            Livewire.hook('message.processed', safeMasonry);
        });

    async function convertAddressToCoords() {
        const address = document.getElementById('address').value;
        const apiKey = '{{ config("services.google_maps.key") }}';

        const response = await fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${apiKey}`);
        const data = await response.json();

        if (data.status === 'OK') {
            const location = data.results[0].geometry.location;
            document.getElementById('latitude').value = location.lat;
            document.getElementById('longitude').value = location.lng;
            return true;
        } else {
            alert('Location not found.');
            return false;
        }
    }
</script>

</x-app-layout>
