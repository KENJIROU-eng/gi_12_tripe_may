<nav x-data="{ open: false, planOpen: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 fixed top-0 left-0 right-0 z-40 shadow h-16">
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-full">
        <div class="h-full relative flex items-center justify-center">

            {{-- 左：ロゴと今日の予定 --}}
            <div class="absolute left-0 flex items-center gap-6 h-full space-x-8">
                {{-- ロゴ --}}
                <div class="flex items-center space-x-2">
                    <a href="{{ route('dashboard') }}" class="h-10 w-10 flex-shrink-0">
                        <img src="{{ asset('images/tripeas_logo_20250617.png') }}" alt="logo" class="h-full w-full object-cover rounded-md">
                    </a>
                    <span class="text-lg font-semibold text-gray-900 dark:text-gray-100 tracking-wide">Tripe@s</span>
                </div>

                {{-- 今日の予定ボタン（PC） --}}
                <div class="hidden lg:flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400 cursor-pointer" @click="planOpen = true">
                    <i class="fa-solid fa-calendar-day"></i>
                    <span>Today's Plan</span>
                </div>

                {{-- 今日の予定ボタン（SP） --}}
                <div class="lg:hidden flex items-center text-indigo-600 dark:text-indigo-400 cursor-pointer"
                    @click="console.log('clicked'); open = false; planOpen = true">
                    <i class="fa-solid fa-calendar-day text-xl"></i>
                </div>

            </div>

            {{-- モーダル --}}
            <div x-show="planOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div @click.away="planOpen = false" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Today's Plan</h2>
                    @if($todayItineraries && $todayItineraries->count())
                        <ul class="text-sm text-gray-700 dark:text-gray-200 space-y-1">
                            @foreach($todayItineraries as $itinerary)
                                <li class="grid grid-cols-[60px_1fr]">
                                    <span class="font-medium text-gray-500 dark:text-gray-200">Date:</span>
                                    <span>{{ \Carbon\Carbon::parse($itinerary->start_date)->format('M. d, Y') }}
                                        ～ {{ \Carbon\Carbon::parse($itinerary->end_date)->format('M. d, Y') }}</span>
                                </li>
                                <li class="grid grid-cols-[60px_1fr]">
                                    <span class="font-medium text-gray-500 dark:text-gray-200">Title:</span>
                                    <a href="{{ route('itinerary.show', $itinerary->id) }}" class="text-blue-500 hover:underline">{{ Str::limit($itinerary->title, 20) }}</a>
                                </li>
                                <li class="grid grid-cols-[60px_1fr]">
                                    <span class="font-medium text-gray-500 dark:text-gray-200">Group:</span>
                                    <a href="{{ route('message.show', $itinerary->group->id) }}" class="text-blue-500 hover:underline">{{ Str::limit($itinerary->group->name, 20) }}</a>
                                </li>
                                <hr>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">No plans for today.</p>
                    @endif
                    <div class="mt-4 text-right">
                        <button @click="planOpen = false"
                                class="px-4 py-2 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">Close</button>
                    </div>
                </div>
            </div>

            {{-- 中央：メニュー --}}
            <div class="absolute left-1/2 transform -translate-x-1/2 hidden sm:flex items-center space-x-8">
                @auth
                    <x-nav-link :href="route('post.list')" :active="request()->routeIs('post*')">Post</x-nav-link>
                    <x-nav-link :href="route('itinerary.index')" :active="request()->routeIs('itinerary*')">Itinerary</x-nav-link>
                    <x-nav-link :href="route('groups.index')" :active="request()->routeIs('group*')">Group</x-nav-link>
                @endauth
            </div>

            {{-- 右：通知・ドロップダウン・ハンバーガー --}}
            <div class="absolute right-0 flex items-center space-x-8 h-full">
                {{-- 通知 --}}
                @if ($groupIds)
                    <div x-data="{ notificationOpen: false }" class="relative">
                        <button @click.stop="notificationOpen = !notificationOpen"
                                class="relative px-2 text-gray-600 dark:text-gray-200 hover:text-yellow-500">
                            <i class="fa-solid fa-bell text-lg"></i>
                            @if ($nonReadCount_total > 0)
                                <span class="absolute -top-1 -right-1 inline-block w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                    {{ $nonReadCount_total }}
                                </span>
                            @endif
                        </button>
                        <div x-show="notificationOpen" @click.outside="notificationOpen = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded shadow-lg z-50">
                            @foreach ($groups as $group)
                                @if ($nonReadCount[$group->id] > 0)
                                    <a href="{{ route('message.show', $group->id) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800">
                                        {{ $group->name }}
                                        <span class="ml-2 inline-block text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                                            {{ $nonReadCount[$group->id] }}
                                        </span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ドロップダウンとハンバーガー --}}
                <div class="flex items-center space-x-4">
                    @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="w-8 h-8 rounded-full overflow-hidden border-2 {{ $nonReadCount_total > 0 ? 'border-red-500' : 'border-transparent' }}">
                                    <img src="{{ Auth::user()->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="Avatar" class="w-full h-full object-cover">
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @can('admin')
                                    <x-dropdown-link :href="route('admin.users.show')">Admin</x-dropdown-link>
                                @endcan
                                <x-dropdown-link :href="route('profile.show', Auth::id())">Profile</x-dropdown-link>
                                <x-dropdown-link :href="route('profile.users.list')">Search Users</x-dropdown-link>
                                <x-dropdown-link :href="route('settings')">Settings</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link href="#" onclick="event.preventDefault(); clearAudioSettings(); this.closest('form').submit();">
                                        Log Out
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @endauth

                    {{-- ハンバーガー（モバイル） --}}
                    <button @click="open = !open" class="p-2 focus:outline-none sm:hidden">
                        <svg class="h-6 w-6 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!open" d="M4 6h16M4 12h16M4 18h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path x-show="open" d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- モバイルメニュー --}}
    <div x-show="open" x-cloak class="fixed inset-0 z-30">
        <div class="absolute inset-0 bg-black bg-opacity-50" @click="open = false"></div>
        <div class="relative w-64 bg-white dark:bg-gray-900 p-4 h-full overflow-y-auto z-40">
            @auth
                <x-responsive-nav-link :href="route('post.list')" :active="request()->routeIs('post*')">Post</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('itinerary.index')" :active="request()->routeIs('itinerary*')">Itinerary</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('groups.index')" :active="request()->routeIs('group*')">Group</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.show', Auth::id())">Profile</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('settings')">Settings</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link href="#" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-responsive-nav-link>
                </form>
            @endauth
        </div>
    </div>

    <script>
        function clearAudioSettings() {
            const userId = document.body.dataset.userId;
            if (userId) {
                localStorage.removeItem(`audioUnlocked_user_${userId}`);
                localStorage.removeItem(`notificationsEnabled_user_${userId}`);
            }
        }
    </script>
</nav>
