{{-- <nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 overflow-x-hidden">
    <div class="mx-auto sm:px-4 lg:px-6">
        <div class="flex justify-between h-16">
            <div class="w-28 my-auto">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="block h-12 w-12">
                        <img src="{{ asset('images/tripeas_logo_20250617.png') }}" alt="application logo" class="w-full h-full object-cover">
                    </a>
                    <span class="ms-3 text-sm 2xl:text-xl font-semibold title-sm">Tripe@s</span>
                </div>
            </div>
            <div id="notification-area" class="fixed top-4 right-4 space-y-2 z-50"></div>

            @if (Auth::check())
                <div class="flex-1 mx-4 2xl:mx-20 mt-auto grid grid-cols-3 nav-sm">
                    <div class="text-center">
                        <x-nav-link :href="route('post.list')" :active="request()->routeIs('post*')" class="text-xs lg:text-lg">
                            {{ __('Post') }}
                        </x-nav-link>
                    </div>
                    <div class="text-center border-x-2 border-gray-300 dark:border-gray-600">
                        <x-nav-link :href="route('itinerary.index')" :active="request()->routeIs('itinerary*')" class="text-xs lg:text-lg">
                            {{ __('Itinerary') }}
                        </x-nav-link>
                    </div>
                    <div class="text-center">
                        <x-nav-link :href="route('groups.index')" :active="request()->routeIs('group*')" class="text-xs lg:text-lg">
                            {{ __('Group') }}
                        </x-nav-link>
                    </div>
                </div>

                <div class="flex items-center space-x-2 my-auto">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if ($nonReadCount_total > 0)
                                <button aria-label="User Menu" class="w-8 h-8 rounded-full overflow-hidden focus:outline-none focus:ring border-2 border-red-400">
                                    @if (Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 object-cover rounded-full" />
                                    @else
                                        <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="w-8 h-8 object-cover rounded-full">
                                    @endif
                                </button>
                            @else
                                <button aria-label="User Menu" class="w-8 h-8 rounded-full overflow-hidden focus:outline-none focus:ring">
                                    @if (Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 object-cover rounded-full" />
                                    @else
                                        <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="w-8 h-8 object-cover rounded-full">
                                    @endif
                                </button>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            @can('admin')
                                <x-dropdown-link :href="route('admin.users.show')">
                                    {{ __('Admin') }}
                                </x-dropdown-link>
                            @endcan
                            <x-dropdown-link :href="route('profile.show', Auth::user()->id)">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.users.list')">
                                {{ __('Search Users') }}
                            </x-dropdown-link>
                            @if ($groupIds)
                                <div x-data="{ open: false }" class="relative inline-block">
                                    <button @click.stop="open = !open"
                                            class="w-full flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <span>
                                        {{ __('New Messages') }}
                                        @if ($nonReadCount_total > 0)
                                            <span class="ml-1 inline-block text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                                            {{ $nonReadCount_total }}
                                            </span>
                                        @endif
                                    </span>
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                    </button>

                                    <div x-show="open" @click.outside="open = false"
                                        class="absolute top-0 right-0 ml-2 w-44 bg-white border border-gray-200 rounded-md shadow-lg z-50
                                                transform translate-x-full"
                                        x-cloak>
                                    @foreach ($groups as $group)
                                        @if ($nonReadCount[$group->id] > 0)
                                        <a href="{{ route('message.show', $group->id) }}"
                                            class="flex justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <span>{{ $group->name }}</span>
                                            <span class="inline-block text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                                            {{ $nonReadCount[$group->id] }}
                                            </span>
                                        </a>
                                        @endif
                                    @endforeach
                                    </div>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); clearAudioSettings(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                    <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</span>
                </div>

                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = !open" class="p-2 rounded-md transition duration-200 focus:outline-none">
                        <svg class="h-6 w-6 text-gray-800 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
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
</nav> --}}

<nav x-data="{ open: false }"
    class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 fixed top-0 left-0 right-0 z-40">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('dashboard') }}" class="h-12 w-12 flex-shrink-0">
                    <img src="{{ asset('images/tripeas_logo_20250617.png') }}" alt="logo" class="h-full w-full object-cover">
                </a>
                <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tripe@s</span>
            </div>

            <!-- デスクトップメニュー -->
            <div class="hidden sm:flex space-x-8">
                @if(Auth::check())
                    <x-nav-link :href="route('post.list')" :active="request()->routeIs('post*')" class="text-xs lg:text-lg nav-sm">Post</x-nav-link>
                    <div class="border-x-2 border-gray-300 dark:border-gray-600 px-2">
                        <x-nav-link :href="route('itinerary.index')" :active="request()->routeIs('itinerary*')" class="text-xs lg:text-lg nav-sm">Itinerary</x-nav-link>
                    </div>
                    <x-nav-link :href="route('groups.index')" :active="request()->routeIs('group*')" class="text-xs lg:text-lg nav-sm">Group</x-nav-link>
                @endif
            </div>

            <!-- ドロップダウン / ハンバーガー -->
            <div class="flex items-center space-x-4">
                @if(Auth::check())
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button aria-label="User Menu" class="w-8 h-8 rounded-full overflow-hidden focus:outline-none focus:ring">
                                @if ($nonReadCount_total > 0)
                                    @if (Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 object-cover rounded-full border-2 border-red-500" />
                                    @else
                                        <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="w-8 h-8 object-cover rounded-full border-2 border-red-500">
                                    @endif
                                @else
                                    @if (Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 object-cover rounded-full" />
                                    @else
                                        <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="w-8 h-8 object-cover rounded-full">
                                    @endif
                                @endif
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @can('admin')
                                <x-dropdown-link :href="route('admin.users.show')">
                                    {{ __('Admin') }}
                                </x-dropdown-link>
                            @endcan
                            <x-dropdown-link :href="route('profile.show', Auth::id())">
                                Profile
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.users.list')">
                                Search Users
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('settings')">
                                Settings
                            </x-dropdown-link>
                            @if ($groupIds)
                                <div x-data="{ open: false }" class="relative inline-block">
                                    <button @click.stop="open = !open"
                                            class="w-full flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <span>
                                        {{ __('New Messages') }}
                                        @if ($nonReadCount_total > 0)
                                            <span class="ml-1 inline-block text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                                            {{ $nonReadCount_total }}
                                            </span>
                                        @endif
                                    </span>
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                    </button>

                                    <div x-show="open" @click.outside="open = false"
                                        class="absolute top-0 right-0 ml-2 w-44 bg-white border border-gray-200 rounded-md shadow-lg z-50
                                                transform translate-x-full"
                                        x-cloak>
                                    @foreach ($groups as $group)
                                        @if ($nonReadCount[$group->id] > 0)
                                        <a href="{{ route('message.show', $group->id) }}"
                                            class="flex justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <span>{{ $group->name }}</span>
                                            <span class="inline-block text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                                            {{ $nonReadCount[$group->id] }}
                                            </span>
                                        </a>
                                        @endif
                                    @endforeach
                                    </div>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link href="#" onclick="event.preventDefault(); clearAudioSettings(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endif

                <!-- Hamburger Menu (Mobile) -->
                <button @click="open = !open"
                    class="sm:hidden p-2 rounded-md focus:outline-none focus:ring"
                    aria-label="Menu toggle">
                    <svg :class="{ 'rotate-90': open }" class="h-6 w-6 transform transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open }" class="inline-flex" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open }" class="hidden" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- モバイルメニュー -->
    <div x-show="open" @click.outside="open = false"
        x-cloak
        class="sm:hidden fixed inset-0 bg-black bg-opacity-40 z-30 flex">
        <div class="w-64 bg-white dark:bg-gray-900 p-4 overflow-y-auto">
            <div class="space-y-4">
                @if(Auth::check())
                    <x-responsive-nav-link :href="route('post.list')" :active="request()->routeIs('post*')">Post</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('itinerary.index')" :active="request()->routeIs('itinerary*')">Itinerary</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('groups.index')" :active="request()->routeIs('group*')">Group</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.show', Auth::id())">Profile</x-responsive-nav-link>
                    <hr class="border-gray-200 my-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link href="#" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-responsive-nav-link>
                    </form>
                @endif
            </div>
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
