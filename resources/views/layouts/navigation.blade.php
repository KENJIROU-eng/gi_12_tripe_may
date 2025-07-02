<nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 fixed top-0 left-0 right-0 z-40 shadow h-16">
    <div x-data="{ open: false, planOpen: false }" class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-full">
        <div class="h-full relative flex items-center justify-between">

            {{-- 左：ロゴと今日の予定 --}}
            <div class="flex items-center gap-6 h-full flex-shrink-0">
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
                <button type="button" class="lg:hidden flex items-center justify-center text-indigo-600 dark:text-indigo-400 cursor-pointer focus:outline-none" @click="planOpen = true" aria-label="Open today's plan">
                    <i class="fa-solid fa-calendar-day text-xl"></i>
                </button>
            </div>

            {{-- モーダル --}}
            <div x-show="planOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div @click.away="planOpen = false" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md flex flex-col max-h-[50vh] overflow-hidden">

                    {{-- header --}}
                    <div class="p-4 border-b border-gray-300 dark:border-gray-600">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Today's Plan</h2>
                    </div>

                    {{-- contents --}}
                    <div class="p-4 overflow-y-auto flex-1">
                        @if($todayItineraries && $todayItineraries->count())
                            <ul class="text-sm text-gray-700 dark:text-gray-200 space-y-1">
                                @foreach($todayItineraries as $itinerary)
                                    <li class="grid grid-cols-[60px_1fr]">
                                        <span class="font-medium text-gray-500 dark:text-gray-200">Date:</span>
                                        <span>{{ \Carbon\Carbon::parse($itinerary->start_date)->format('M. d, Y') }} ～ {{ \Carbon\Carbon::parse($itinerary->end_date)->format('M. d, Y') }}</span>
                                    </li>
                                    <li class="grid grid-cols-[60px_1fr]">
                                        <span class="font-medium text-gray-500 dark:text-gray-200">Title:</span>
                                        <a href="{{ route('itinerary.show', $itinerary->id) }}" class="text-blue-500 hover:underline">{{ Str::limit($itinerary->title, 20) }}</a>
                                    </li>
                                    <li class="grid grid-cols-[60px_1fr] mb-2">
                                        <span class="font-medium text-gray-500 dark:text-gray-200">Group:</span>
                                        @if ($itinerary->group)
                                            <a href="{{ route('message.show', $itinerary->group->id) }}" class="text-blue-500 hover:underline">
                                                {{ Str::limit($itinerary->group->name, 20) }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">No group</span>
                                        @endif
                                    </li>
                                    <li><hr class="my-1 border-gray-300 dark:border-gray-600"></li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">No plans for today.</p>
                        @endif
                    </div>

                    {{-- footer --}}
                    <div class="px-4 py-2 border-t border-gray-300 dark:border-gray-600 text-right">
                        <button @click="planOpen = false" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">Close</button>
                    </div>
                </div>
            </div>

            {{-- 中央：メニュー --}}
            <div class="absolute left-1/2 transform -translate-x-1/2 hidden sm:flex text-xl items-center space-x-6">
                @auth
                    {{-- Post --}}
                    <a href="{{ route('post.list') }}"
                    class="relative inline-block transition-all duration-200
                        {{ request()->routeIs('post*') ? 'text-red-500 font-bold scale-150' : 'text-gray-500 scale-90 hover:text-red-500 hover:scale-100' }}">
                        <span class="relative inline-block">
                            <span class="relative z-10">Post</span>
                            <i class="fa-solid fa-camera absolute inset-0 flex items-center justify-center
                                {{ request()->routeIs('post*') ? 'text-red-300 opacity-40' : 'text-red-300 opacity-30' }}
                                text-3xl pointer-events-none"></i>
                        </span>
                    </a>

                    {{-- 仕切り線 --}}
                    <span class="text-gray-300 dark:text-gray-500 select-none">｜</span>

                    {{-- Itinerary --}}
                    <a href="{{ route('itinerary.index') }}"
                    class="relative inline-block transition-all duration-200
                        {{ request()->routeIs('itinerary*') ? 'text-blue-500 font-bold scale-150' : 'text-gray-500 scale-90 hover:text-blue-500 hover:scale-100' }}">
                        <span class="relative inline-block">
                            <span class="relative z-10">Itinerary</span>
                            <i class="fa-solid fa-road absolute inset-0 flex items-center justify-center
                                {{ request()->routeIs('itinerary*') ? 'text-blue-300 opacity-40' : 'text-blue-300 opacity-30' }}
                                text-3xl pointer-events-none"></i>
                        </span>
                    </a>

                    {{-- 仕切り線 --}}
                    <span class="text-gray-300 dark:text-gray-500 select-none">｜</span>

                    {{-- Group --}}
                    <a href="{{ route('groups.index') }}"
                    class="relative inline-block transition-all duration-200
                        {{ request()->routeIs('group*') ? 'text-yellow-500 font-bold scale-150' : 'text-gray-500 scale-90 hover:text-yellow-500 hover:scale-100' }}">
                        <span class="relative inline-block">
                            <span class="relative z-10">Group</span>
                            <i class="fa-solid fa-comments absolute inset-0 flex items-center justify-center
                                {{ request()->routeIs('group*') ? 'text-yellow-300 opacity-40' : 'text-yellow-300 opacity-30' }}
                                text-3xl pointer-events-none"></i>
                        </span>
                    </a>
                @endauth
            </div>

            {{-- 右：通知・サウンド・ドロップダウン・ハンバーガー --}}
            <div class="flex items-center h-full gap-2 sm:gap-4 pr-2 flex-shrink-0">
                {{-- 通知 --}}
                @if ($groupIds)
                    <div x-data="{ notificationOpen: false }" class="relative">
                        <button data-notification-button @click.stop="notificationOpen = !notificationOpen" class="relative px-2 sm:ms-4 text-gray-600 dark:text-gray-200 hover:text-green-500 focus:outline-none focus:ring-0 focus:border-transparent" aria-label="Notification" title="Notification">

                            <i class="fa-solid fa-comment-dots text-xl"></i>
                            @if ($nonReadCount_total > 0)
                                <span class="absolute -top-1 -right-1 inline-block w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                    {{ $nonReadCount_total }}
                                </span>
                            @endif
                        </button>
                        <div x-show="notificationOpen" @click.outside="notificationOpen = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded shadow-lg z-50">
                            @php $hasUnread = false; @endphp
                            @foreach ($groups as $group)
                                @if ($nonReadCount[$group->id] > 0)
                                    @php $hasUnread = true; @endphp
                                    <a href="{{ route('message.show', $group->id) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800">
                                        {{ $group->name }}
                                        <span class="ml-2 inline-block text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                                            {{ $nonReadCount[$group->id] }}
                                        </span>
                                    </a>
                                @endif
                            @endforeach
                            @if (! $hasUnread)
                                <div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">
                                    No notification
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- サウンドトグルボタン（ON/OFF） --}}
                <button id="sound-toggle" class=" text-gray-600 dark:text-gray-200 hover:text-yellow-500 focus:outline-none" aria-label="Toggle notification sound" title="Toggle notification sound">
                    <i id="sound-icon" class="fa-solid fa-bell text-xl"></i>
                    <span id="sound-status" class="ml-1 text-sm text-gray-500 dark:text-gray-300 hidden sm:inline-block">
                        ON
                    </span>
                </button>

                {{-- ドロップダウンとハンバーガー --}}
                {{-- ドロップダウン（PC専用） --}}
                <div class="hidden sm:flex items-center space-x-4">
                    @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="w-8 h-8 rounded-full overflow-hidden border-2 {{ $nonReadCount_total > 0 ? 'border-red-500' : 'border-transparent' }}">
                                    <img src="{{ Auth::user()->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="Avatar" class="w-full h-full object-cover">
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @can('admin')
                                    <x-dropdown-link :href="route('admin.users.show')"><i class="fa-solid fa-user-secret"></i> Admin</x-dropdown-link>
                                @endcan
                                <x-dropdown-link :href="route('profile.show', Auth::id())"><i class="fa-solid fa-address-card"></i> Profile</x-dropdown-link>
                                <x-dropdown-link :href="route('profile.users.list')"><i class="fa-solid fa-magnifying-glass"></i> Search Users</x-dropdown-link>
                                <x-dropdown-link :href="route('settings')"><i class="fa-solid fa-gear"></i> Settings</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link href="#" onclick="event.preventDefault(); clearAudioSettings(); this.closest('form').submit();">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @endauth
                </div>

                {{-- ハンバーガー（モバイル専用） --}}
                <div class="flex sm:hidden items-center">
                    <button @click="open = !open" class="p-2 focus:outline-none">
                        <svg class="h-6 w-6 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!open" d="M4 6h16M4 12h16M4 18h16"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path x-show="open" d="M6 18L18 6M6 6l12 12"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        {{-- モバイルメニュー --}}
        <div x-show="open" x-cloak class="fixed inset-0 z-30">
            <div class="absolute inset-0 bg-black bg-opacity-50" @click="open = false"></div>
            <div class="relative w-64 bg-white dark:bg-gray-900 p-4 h-full overflow-y-auto z-40">
                @auth
                    @can('admin')
                        <x-responsive-nav-link :href="route('admin.users.show')"><i class="fa-solid fa-user-secret"></i> Admin</x-responsive-nav-link>
                    @endcan
                    <x-responsive-nav-link :href="route('post.list')" :active="request()->routeIs('post*')"><i class="fa-solid fa-camera text-red-500 opacity-70"></i> Post</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('itinerary.index')" :active="request()->routeIs('itinerary*')"><i class="fa-solid fa-road text-blue-500 opacity-70"></i> Itinerary</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('groups.index')" :active="request()->routeIs('group*')"><i class="fa-solid fa-comments text-yellow-500 opacity-70"></i> Group</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.show', Auth::id())"><i class="fa-solid fa-address-card"></i> Profile</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.users.list')"><i class="fa-solid fa-magnifying-glass"></i> Search Users</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('settings')"><i class="fa-solid fa-gear"></i> Settings</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link href="#" onclick="event.preventDefault(); this.closest('form').submit();"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</x-responsive-nav-link>
                    </form>
                @endauth
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

        // 通知音をグローバルに保持
        let notificationSound;

        window.addEventListener('DOMContentLoaded', () => {
            const userId = document.body.dataset.userId || 'default';
            const toggleBtn = document.getElementById('sound-toggle');
            const icon = document.getElementById('sound-icon');
            const statusText = document.getElementById('sound-status');

            // 通知音を事前に読み込み
            notificationSound = new Audio('/sounds/maou_se_onepoint23.mp3');
            notificationSound.preload = 'auto';
            notificationSound.volume = 1;

            // 状態の初期化
            const audioUnlocked = localStorage.getItem(`audioUnlocked_user_${userId}`) === '1';
            updateUI(audioUnlocked);

            toggleBtn.addEventListener('click', () => {
                const current = localStorage.getItem(`audioUnlocked_user_${userId}`) === '1';
                const next = !current;

                if (next) {
                    const dummy = new Audio('/sounds/maou_se_onepoint23.mp3');
                    dummy.volume = 0;
                    dummy.play().then(() => {
                        localStorage.setItem(`audioUnlocked_user_${userId}`, '1');
                        console.log('🔊 Sound ON');
                        updateUI(true);
                    }).catch(err => {
                        console.warn('❌ Sound permission denied:', err);
                    });
                } else {
                    localStorage.setItem(`audioUnlocked_user_${userId}`, '0');
                    console.log('🔇 Sound OFF');
                    updateUI(false);
                }
            });

            function updateUI(enabled) {
                if (enabled) {
                    icon.classList.replace('fa-bell-slash', 'fa-bell');
                    icon.classList.add('text-yellow-500');
                    icon.classList.remove('text-gray-600');
                    if (statusText) statusText.textContent = 'ON';
                } else {
                    icon.classList.replace('fa-bell', 'fa-bell-slash');
                    icon.classList.remove('text-yellow-500');
                    icon.classList.add('text-gray-600');
                    if (statusText) statusText.textContent = 'OFF';
                }
            }

            // グローバル再生関数
            window.playNotificationSound = function () {
                const enabled = localStorage.getItem(`audioUnlocked_user_${userId}`) === '1';
                if (enabled && notificationSound) {
                    notificationSound.currentTime = 0;
                    notificationSound.play().catch(e => console.warn('🔕 Sound play failed:', e));
                }
            };

            // 🔔 通知音をページロード時に鳴らすかのロジックを変更
            const currentCount = {{ $nonReadCount_total }};
            const countKey = `prevNonReadCount_user_${userId}`;
            const prevCount = parseInt(localStorage.getItem(countKey) || '0');

            if (currentCount > prevCount) {
                playNotificationSound();
            }

            // 通知を開いたときに前回カウントを更新（これを通知表示時にも追加）
            const notificationButton = document.querySelector('[data-notification-button]');
            if (notificationButton) {
                notificationButton.addEventListener('click', () => {
                    if (currentCount > 0) {
                        playNotificationSound();
                    }
                    localStorage.setItem(countKey, currentCount.toString());
                });
            }

            // fallback: 自動で更新（通知表示がない場合用）
            localStorage.setItem(countKey, currentCount.toString());
        });
    </script>
</nav>
