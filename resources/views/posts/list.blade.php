
<x-app-layout>
    <div style="background-image: url('/images/pexels-fotios-photos-1252983.jpg'); background-size: cover; background-position: center">
        <div class= "mt-5 h-[880px]">
            <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                    <div class="p-6 text-black dark:text-gray-100 h-full">
                        {{-- title --}}
                        <div class="relative flex items-center justify-center h-12 my-4">
                            <h1 class="text-3xl sm:text-3xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Post List</h1>
                            <a href="{{ route('post.create') }}" class="ml-auto mr-3 right-40 no-underline text-end ">
                                <i class="fa-solid fa-plus ml-auto"></i> add Post
                            </a>
                        </div>
                        {{-- contents --}}
                        <div class="mx-auto  mt-4 ">
                            <form action="{{ route('post.search') }}" method="get" class="w-full">
                                <div class="flex justify-center items-center mb-3 w-full">
                                    <select name="search" class="block border border-gray-300 rounded w-2/3 focus:ring-2 me-3">
                                        <option value="" selected disabled>Search Category</option>
                                        @foreach ($all_categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="block text-white px-4 bg-green-500 py-2 font-semi-bold hover: border-green-500 hover:bg-green-600 transition duration-300 rounded-md">Search</button>
                                </div>
                            </form>
                            {{-- @error('search')
                                <div class="text-danger small text-center">{{ $message }}</div>
                            @enderror --}}
                            <div id="scroll-container" class="max-h-[660px] overflow-auto pb-12">
                                <div id="post-container" class="flex flex-wrap -mx-2" data-masonry='{"itemSelector": ".post-item", "columnWidth": ".post-sizer", "percentPosition": true }'>
                                    <div class="post-sizer w-full sm:w-1/2 lg:w-1/3"></div>

                                    @foreach ($all_posts as $post)
                                        <div class="post-item w-full sm:w-1/2 lg:w-1/3 px-4 mb-6 transition duration-300 ease-in-out transform hover:scale-[1.03] hover:-translate-y-1 ">
                                            <div class="bg-white dark:bg-gray-700 shadow-xl  overflow-hidden">
                                                <a href="{{ route('post.show', $post->id) }}">
                                                    <img src="{{ $post->image }}" alt="{{ $post->title }}"
                                                        class="w-full h-auto object-cover transition-transform duration-300 hover:scale-100">
                                                </a>
                                                <p class="text-gray-500 font-extralight text-xs text-center mt-1">{{ $post->user->name }}</p>
                                                <div class="flex items-center px-3 pb-3">
                                                    <a href="{{ route('profile.show', $post->user->id) }}">
                                                        @if ($post->user->avatar)
                                                            <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="object-cover rounded-full w-12 h-12 hover:scale-110 duration-300 transition">
                                                        @else
                                                            <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="object-cover rounded-full w-12 h-12 hover:scale-110 duration-300 transition">
                                                        @endif
                                                    </a>
                                                    <h2 class="text-xl font-bold mx-auto truncate max-w-[150px]">{{ $post->title }}</h2>
                                                    {{-- <i class="fa-solid fa-heart text-red-500 mr-2"></i>{{ $post->likes()->count() }} --}}
                                                    <div class="flex items-center">
                                                        <div class="ml-4">
                                                            @if (in_array(Auth::User()->id, $post->likes->pluck('user_id')->toArray()) )
                                                                <form action="{{ route('post.like.delete',$post->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit">
                                                                        <i class="fa-solid fa-heart text-red-500 text-xl mr-2"></i>
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <a href="{{ route('post.like',$post->id) }}">
                                                                    <i class="fa-regular fa-heart text-gray-400 hover:text-red-500 text-xl mr-2"></i>
                                                                </a>
                                                            @endif
                                                        </div>

                                                        <div x-data="{open:false}" class="mr-4">
                                                            <button @click="open = true">
                                                                {{ $post->likes()->count() }}
                                                            </button>
                                                            @include('posts.modals.likeUser_list', ['post' => $post])
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- Masonry.js CDN --}}
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Masonry('#post-container', {
            itemSelector: '.post-item',
            columnWidth: '.post-sizer',
            percentPosition: true
        });
    });
</script>

</x-app-layout>

