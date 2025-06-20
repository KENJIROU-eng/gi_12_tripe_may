<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="mx-auto sm:px-4 lg:px-6">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="w-28 my-auto">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="block h-12 w-12">
                        <img src="{{ asset('images/tripeas_logo_20250617.png') }}" alt="application logo" class="w-full h-full object-cover">
                    </a>
                    <span class="ms-3 text-sm 2xl:text-xl font-semibold title-lg">Tripe@s</span>
                </div>
            </div>
            <div id="notification-area" class="fixed top-4 right-4 space-y-2 z-50"></div>

            @if (Auth::check())
                <!-- Navigation Links -->
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

                <!-- Settings Dropdown -->
                <div class="flex items-center space-x-2 my-auto">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button aria-label="User Menu" class="w-8 h-8 rounded-full overflow-hidden focus:outline-none focus:ring">
                                @if (Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 object-cover rounded-full" />
                                @else
                                    <i class="fa-solid fa-circle-user text-blue-600 text-base lg:text-lg"></i>
                                @endif
                                {{-- <span class="ml-1 inline-block text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                                    {{ $nonReadCount_total }}
                                </span> --}}
                            </button>
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
                            <!--  Messages -->
                            @if ($groupIds)
                                <div x-data="{ open: false }" class="relative">
                                    <button @click.stop="open = !open"
                                        class="w-full flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <span>
                                            {{ __('New Messages') }}
                                            <span class="ml-1 inline-block text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                                                {{ $nonReadCount_total }}
                                            </span>
                                        </span>
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                    <!-- sub menu -->
                                    <div x-show="open" @click.outside="open = false"
                                        class="absolute left-full top-0 mt-0 ml-1 w-44 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                        @foreach ($groups as $group)
                                            @if ($nonReadCount[$group->id] > 0)
                                                <a href="{{ route('message.show', $group->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ $group->name }}</a>
                                                <span class="ml-1 inline-block text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                                                    {{ $nonReadCount[$group->id] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                    <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</span>
                </div>

                <!-- Hamburger (for small screens) -->
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

    {{-- @if (Auth::check())
        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-4 pb-3 space-y-1 border-t border-gray-200 dark:border-gray-600">
                <!-- User Info -->
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <!-- Responsive Navigation Links -->
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('post.list')" :active="request()->routeIs('post*')">
                    {{ __('Post') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('itinerary.index')" :active="request()->routeIs('itinerary*')">
                    {{ __('Itinerary') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('groups.index')" :active="request()->routeIs('group*')">
                    {{ __('Group') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.show', Auth::user()->id)">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    @endif --}}
</nav>
