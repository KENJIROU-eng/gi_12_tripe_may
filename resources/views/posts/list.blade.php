
<x-app-layout>
    <div style="background-image: url('/images/pexels-fotios-photos-1252983.jpg'); background-size: cover; background-position: center">
        <div class= "mt-5 h-[880px]">
            <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                    <div class="p-6 text-black dark:text-gray-100 h-full">
                        {{-- title --}}
                        <div class="relative flex items-center justify-center h-16 my-5">
                            <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Post List</h1>
                            <a href="{{ route('post.create') }}" class="absolute right-40 no-underline text-end ">
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
                            <div id="scroll-container" class="max-h-[660px] overflow-auto">
                                {{-- <div id="post-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-14 mx-auto "> --}}
                                <div id="post-container" class="columns-1 sm:columns-2 lg:columns-3 gap-4 ml-4">
                                    @foreach ($all_posts as $post)
                                        <div x-data="{ showModal: false }" class="w-11/12 transform transition duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer relative h-auto mb-5 shadow-xl">
                                            <a href="{{ route('post.show', $post->id) }}">
                                                <img src="{{ $post->image }}" alt="{{ $post->title }}" class=" shadow w-full max-h-96 object-cover">
                                            </a>
                                            <div class="flex p-3 items-center">
                                                    @if ($post->user->avatar)
                                                        <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="object-cover rounded-full w-12 h-12">
                                                    @else
                                                        <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="object-cover rounded-full w-12 h-12">
                                                    @endif
                                                <h2 class="text-xl font-bold pl-4 mx-auto">{{ $post->title }}</h2>
                                                {{-- <i class="fa-regular fa-trash-can ml-auto hover:text-red-500 cursor-pointer"
                                                    @click="showModal = true"></i> --}}
                                                    <i class="fa-solid fa-heart text-red-500 3xl mr-2"></i>1
                                            </div>
                                            @include('posts.modals.delete')
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            {{-- this is the trigger for IntersectionObserver --}}
                            <div id="load-trigger" data-offset="{{ $all_posts->count() }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const loadTrigger = document.querySelector("#load-trigger");

                if (loadTrigger) {
                    const postContainer = document.querySelector("#post-container");

                    let loading = false;
                    let offset = parseInt(loadTrigger.dataset.offset, 10) || 0;

                    const observer = new IntersectionObserver(async (entries) => {
                        if (entries[0].isIntersecting && !loading) {
                            loading = true;

                            try {
                                const res = await fetch(`/post/load-more?offset=${offset}`);

                                if (res.ok) {
                                    const json = await res.json();
                                    const posts = json.posts;

                                    if (posts.length > 0) {
                                        posts.forEach(post => {
                                        // アバター画像の有無も整理
                                            const avatar = post.user?.avatar
                                                ? post.user.avatar
                                                : '{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}';

                                            postContainer.innerHTML += `
                                                <div x-data="{ showModal: false }" class="w-11/12 transform transition duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer relative h-auto mb-4 shadow">
                                                    <a href="/post/${post.id}/show">
                                                        <img src="${post.image}" alt="${post.title}" class="object-cover shadow w-full max-h-96">
                                                    </a>
                                                    <div class="flex p-3 items-center">
                                                        <img src="${avatar}" alt="${post?.user?.name}" class="object-cover rounded-full w-12 h-12">
                                                        <h2 class="text-xl font-bold pl-4 mx-auto">${post.title}</h2>
                                                        <i class="fa-solid fa-heart text-red-500 3xl mr-2"></i>1
                                                    </div>
                                                </div>`;
                                        });

                                        // 次にロードする時のoffsetを増やしておく
                                        offset += posts.length;
                                        loadTrigger.dataset.offset = offset;
                                    } else {
                                        // これ以上ロードするものがない場合は監視解除
                                        observer.unobserve(loadTrigger);
                                    }
                                } else {
                                    console.error('ロードに失敗しました!', res.status);
                                    observer.unobserve(loadTrigger);
                                }
                            } catch (err) {
                                console.error('フェッチ時にエラー!', err);
                                observer.unobserve(loadTrigger);
                            } finally {
                                loading = false;
                            }
                        }
                    });

                    observer.observe(loadTrigger);
                }
            });
        </script>
    </div>
</x-app-layout>
