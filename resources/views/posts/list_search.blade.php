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

                    {{-- 検索フォーム --}}
                    <div class="mx-auto mt-4">
                        <form action="{{ route('post.search') }}" method="get" class="w-full">
                            <div class="flex justify-center items-center mb-3 w-full">
                                <select name="search" class="block border border-gray-300 rounded w-2/3 focus:ring-2 me-3">
                                    @if (isset($category_search))
                                        <option value="#">All categories</option>
                                        @foreach ($all_categories as $category)
                                            @if ($category->id != $category_search->id)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @else
                                                <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        <option value="#" selected>All categories</option>
                                        @foreach ($all_categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <button type="submit" class="block text-white px-4 bg-teal-500 py-2 font-semibold hover:bg-teal-700 transition rounded-md">Search</button>
                            </div>
                        </form>
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

        document.addEventListener('DOMContentLoaded', safeMasonry);
        document.addEventListener('livewire:load', () => {
            safeMasonry();
            Livewire.hook('message.processed', safeMasonry);
        });
    </script>
</x-app-layout>

