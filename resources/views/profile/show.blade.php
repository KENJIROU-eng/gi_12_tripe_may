<x-app-layout>
    <div class="mt-5 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    {{-- タイトル & 編集リンク --}}
                    <div class="relative mb-6">
                        {{-- 中央寄せタイトル --}}
                        <h1 class="text-xl sm:text-3xl lg:text-4xl font-bold text-center">
                            <span class="text-green-500">{{ $user->name }}</span>`s Profile
                        </h1>

                        {{-- スマホ用：タイトル下に表示 --}}
                        @if (Auth::id() === $user->id)
                            <div class="mt-2 text-center sm:hidden">
                                <a href="{{ route('profile.edit') }}" class="text-sm text-blue-500 hover:underline">
                                    <i class="fa-solid fa-user-pen"></i> Edit Profile
                                </a>
                            </div>
                        @endif

                        {{-- タブレット以上：右上に固定表示 --}}
                        @if (Auth::id() === $user->id)
                            <div class="hidden sm:block">
                                <a href="{{ route('profile.edit') }}"
                                class="absolute top-0 right-0 text-sm sm:text-base text-blue-500 hover:underline whitespace-nowrap">
                                    <i class="fa-solid fa-user-pen"></i> Edit Profile
                                </a>
                            </div>
                        @endif
                    </div>

                    <hr class="border-green-500 mb-6">

                    {{-- プロフィール情報 --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        {{-- アバターとフォロー --}}
                        <div class="flex flex-col items-center">
                            <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-200">
                                @if ($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex justify-center gap-6 mt-4 text-center text-sm sm:text-base">
                                <div>
                                    <strong>{{ $user->post->count() }}</strong><br> {{ Str::plural('Post', $user->post->count()) }}
                                </div>
                                <div>
                                    <a href="{{ route('follower.show', $user->id) }}" class="hover:text-blue-500">
                                        <strong>{{ $user->followers->count() }}</strong><br>{{ Str::plural('Follower', $user->followers->count()) }}
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('following.show', $user->id) }}" class="hover:text-blue-500">
                                        <strong>{{ $user->following->count() }}</strong><br>Following
                                    </a>
                                </div>
                            </div>
                            @if (Auth::id() !== $user->id)
                                <div class="mt-4">
                                    @if ($user->isFollowed())
                                        <div class="flex  gap-2">
                                            <form action="{{ route('follow.delete', $user->id) }}" method="post">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="bg-gray-500 text-white px-4 py-1 rounded-md hover:bg-gray-600">
                                                    Following
                                                </button>
                                            </form>
                                            @if ($group)
                                                <a href="{{ route('message.show', $group->id) }}" class="block bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600">
                                                    message
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <form action="{{ route('follow.create', $user->id) }}" method="post">
                                            @csrf
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600">
                                                Follow
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- ユーザー詳細 --}}
                        <div class="md:col-span-2">
                            <div class="mb-4">
                                <h3 class="text-gray-500 text-sm sm:text-base">Username</h3>
                                <p class="text-xl sm:text-2xl font-semibold">{{ $user->name }}</p>
                            </div>
                            <div>
                                <h3 class="text-gray-500 text-sm sm:text-base">Introduction</h3>
                                <div class="bg-gray-50 p-3 rounded-md text-gray-800 dark:text-gray-200">
                                    {{ $user->introduction ?? 'No introduction provided.' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 投稿一覧 --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-10 max-h-[600px] overflow-y-auto pr-2">
                        @foreach ($all_posts as $post)
                            <a href="{{ route('post.show', $post->id) }}" class="hover:scale-105 transition-transform duration-150">
                                <div class="aspect-square bg-blue-100 rounded overflow-hidden border border-green-100">
                                    <img src="{{ $post->image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
