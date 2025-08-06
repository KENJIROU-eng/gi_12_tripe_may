<x-app-layout>
    <div class="mt-5 h-[905px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-4 text-black dark:text-gray-100 h-full">

                    {{-- タイトル --}}
                    <div class="relative flex items-center justify-center h-8 my-2">
                        <h1 class="text-3xl sm:text-3xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Post List</h1>
                        <a href="{{ route('post.create') }}" class="ml-auto mr-3 right-40 no-underline text-end text-md sm:text-xl text-teal-500 hover:text-teal-700 bg-white border border-teal-500 hover:border-teal-700 rounded-3xl shadow-md p-2">
                            <i class="fa-solid fa-plus ml-auto"></i> Add Post
                        </a>
                    </div>

                    {{-- 検索フォーム（カテゴリと位置の統合） --}}
                    <div class="mx-auto mt-6 mb-6 w-full">
                        <div class="flex flex-col sm:flex-row gap-6 justify-center items-end flex-wrap">

                            {{-- カテゴリ検索 --}}
                            <form action="{{ route('post.search') }}" method="GET" class="flex flex-col">
                                <label class="text-sm font-semibold mb-1">Category</label>
                                <div class="flex gap-2">
                                    <select name="search" class="border border-gray-300 rounded px-4 py-2 w-60">
                                        <option value="#" {{ !isset($category_search) ? 'selected' : '' }}>All categories</option>
                                        @foreach ($all_categories as $category)
                                            <option value="{{ $category->id }}" {{ isset($category_search) && $category_search->id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit"
                                            class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-700 transition">
                                        Search
                                    </button>
                                </div>
                            </form>

                            {{-- 位置検索 --}}
                            <form id="searchForm" action="{{ route('post.search.location') }}" method="GET" class="flex flex-col">
                                <label class="text-sm font-semibold mb-1">Location Search</label>
                                <div class="flex gap-2 flex-wrap sm:flex-nowrap">
                                    <input type="text" id="address" name="address" placeholder="Enter city or area"
                                        class="border p-2 rounded w-60" required>
                                    <input type="number" name="radius" id="radius" value="20" min="1"
                                        class="border p-2 rounded w-32" placeholder="Radius (km)">
                                    <input type="hidden" name="latitude" id="latitude">
                                    <input type="hidden" name="longitude" id="longitude">
                                    <button type="submit"
                                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                                        Nearby
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>


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
                                            <div class="flex items-center px-3 pb-3 pointer-events-auto">
                                                <a href="{{ route('profile.show', $post->user->id) }}">
                                                    @if ($post->user->avatar)
                                                        <img src="{{ $post->user->avatar }}" class="object-cover rounded-full w-12 h-12 hover:scale-110 transition border border-white shadow-lg">
                                                    @else
                                                        <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" class="object-cover rounded-full w-12 h-12 hover:scale-110 transition">
                                                    @endif
                                                </a>
                                                <h2 class="text-xl font-bold mx-auto truncate max-w-[150px] text-white">{{ $post->title }}</h2>

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

    document.addEventListener('DOMContentLoaded', () => {
        // ✅ safeMasonry の初期化
        safeMasonry();

        // ✅ Livewire 用 hook
        if (window.Livewire) {
            Livewire.hook('message.processed', safeMasonry);
        }

        // ✅ Nearby ボタンの検索処理
        const form = document.getElementById('searchForm');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const address = document.getElementById('address').value.trim();
            const radiusInput = document.getElementById('radius');
            const apiKey = '{{ config("services.google_maps.key") }}';
            const baseUrl = "{{ route('post.search.location') }}";

            let radius = parseInt(radiusInput.value, 10) || 20;

            try {
                const response = await fetch(
                    `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${apiKey}`
                );
                const data = await response.json();

                if (data.status === 'OK') {
                    const location = data.results[0].geometry.location;
                    const types = data.results[0].types;

                    // 国名だったら最低1000km
                    if (types.includes('country') && radius < 1000) {
                        radius = 1000;
                        radiusInput.value = 1000;
                    }

                    const query = new URLSearchParams({
                        address: address,
                        latitude: location.lat.toString(),
                        longitude: location.lng.toString(),
                        radius: radius.toString()
                    });

                    const fullUrl = `${baseUrl}?${query.toString()}`;
                    console.log("Redirecting to:", fullUrl);
                    window.location.assign(fullUrl);

                } else {
                    alert('Location not found. Try a more specific city or area.');
                }
            } catch (error) {
                alert('Failed to fetch location data.');
                console.error(error);
            }
        });
    });
</script>


</x-app-layout>

