<x-app-layout>
    <div class="mt-2 min-h-screen">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-black dark:text-gray-100">
                    {{-- title --}}
                    <div class="relative bg-gradient-to-r from-green-600 to-green-300 text-white rounded-2xl shadow-lg p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between overflow-hidden my-8 mx-4 sm:mx-auto max-w-6xl">
                        {{-- テキスト --}}
                        {{-- <div class="flex-row">
                            <p class="bg-green-500">text</p>
                            <p class="bg-red-500">text</p>
                            <p class="bg-orange-500">text</p>
                            <p class="bg-amber-500">text</p>
                            <p class="bg-yellow-500">text</p>
                            <p class="bg-lime-500">text</p>
                            <p class="bg-emerald-500">text</p>
                            <p class="bg-teal-500">text</p>
                            <p class="bg-cyan-500">text</p>
                            <p class="bg-sky-500">text</p>
                            <p class="bg-blue-500">text</p>
                            <p class="bg-indigo-500">text</p>
                            <p class="bg-violet-500">text</p>
                            <p class="bg-purple-500">text</p>
                            <p class="bg-fuchsia-500">text</p>
                            <p class="bg-pink-500">text</p>
                            <p class="bg-rose-500">text</p>
                        </div> --}}
                        <div class="sm:w-2/3 text-center sm:text-left">
                            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold leading-tight">
                                Welcome to Tripe@s
                            </h1>
                            <p class="mt-2 text-white text-sm sm:text-base">Check your travel schedules & travel posts</p>
                        </div>

                        {{-- 右の画像 --}}
                        <div class="mt-6 sm:mt-0 sm:w-1/3 flex justify-center sm:justify-end">
                            <img src="{{ asset('images/tripeas_logo_20250617.png') }}" alt="Tripe@s Logo"
                                class="w-48 sm:w-48 md:w-48 rounded-full object-cover">
                        </div>
                    </div>

                    {{-- notification --}}
                    <div id="notify-box" class="fixed bottom-4 right-4 bg-white p-4 rounded shadow max-w-sm z-40">
                        <p>Do you wanna receive the notifications?</p>
                        <div class="flex gap-2 mt-4">
                            <button onclick="enableNotification()" class="bg-blue-500 text-white flex-1 py-1 rounded">Yes</button>
                            <button onclick="dismissNotification()" class="bg-gray-300 text-gray-800 flex-1 py-1 rounded">Cancel</button>
                        </div>
                    </div>

                    {{-- modal --}}
                    <div id="audio-permission-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50 hidden">
                        <div class="bg-white rounded-lg p-6 max-w-sm text-center shadow-lg">
                            <p class="text-gray-800 text-lg mb-6">
                                If you permit notification sounds, please click the button below
                            </p>
                            <div class="flex justify-center space-x-4">
                                <button id="enable-sound-btn" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">OK</button>
                                <button id="cancel-sound-btn" class="px-5 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                            </div>
                        </div>
                    </div>
                    {{-- contents --}}
                    <div class="mt-8">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            {{-- calendar --}}
                            <div class="lg:col-span-2">
                                {{-- <div class="mb-4 text-center">
                                    <a href="{{ route('itinerary.share') }}" class="bg-green-500 py-2 px-4 text-lg rounded-md text-white inline-block w-full max-w-md hover:bg-green-600">
                                        <i class="fa-solid fa-plane-departure"></i> Create Itinerary
                                    </a>
                                </div> --}}
                                <div class="relative rounded-xl px-6 py-8 max-w-sm mx-auto mt-10">
                                    <div class="text-center ">
                                        <a href="{{ route('itinerary.share') }}" class="bg-gradient-to-r from-blue-500 via-green-500 to-red-500 py-2 px-4 text-lg text-shadow-lg rounded-md font-bold text-white inline-block w-full max-w-md hover:bg-green-600">
                                            Create Trip Itinerary
                                        </a>
                                    </div>
                                    <div class="absolute -top-2 -right-4 rotate-12">
                                        <div class=" p-3">
                                            <img src="{{ asset('images/de-tuno-3d-hyu.png') }}" alt="" class="w-20 h-20 object-cover opacity-100">
                                        </div>
                                    </div>
                                </div>
                                <h2 class="text-center text-lg font-semibold mb-4">Trip Schedule Calender</h2>
                                <div class="bg-white shadow-lg rounded-lg min-h-[500px] flex flex-col justify-between">
                                    <div class="px-6 py-4 flex justify-between items-center">
                                        <button id="prev-month" class="text-gray-500 hover:text-gray-700">&lt; Previous</button>
                                        <h2 id="month-year" class="text-xl font-semibold"></h2>
                                        <button id="next-month" class="text-gray-500 hover:text-gray-700">Next &gt;</button>
                                    </div>
                                    <div class="grid grid-cols-7 border-b border-gray-200 text-sm">
                                        <template x-for="day in ['Sun.','Mon.','Tue.','Wed.','Thu.','Fri.','Sat.']">
                                            <div><p class="text-center py-2 font-semibold text-gray-600" x-text="day"></p></div>
                                        </template>
                                    </div>
                                    <div id="calendar-body" class="grid grid-cols-7 min-h-[300px] sm:min-h-[400px] lg:min-h-[615px]"></div>
                                </div>
                            </div>
                            {{-- ranking（横並び）とその下に小カレンダー） --}}
                            <div class="bg-white rounded-lg shadow-lg p-4 h-auto space-y-8">
                                {{-- 🔶 Upcoming Posts（上に移動） --}}
                                <div x-data="{ expandedMembers: {} }" class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h2 class="text-center text-lg font-semibold mb-4">Upcoming Trips (Within 1 month)</h2>
                                    <div class="space-y-3 max-h-[320px] overflow-y-auto flex flex-col gap-4">
                                        @foreach ($itineraries as $itinerary)
                                            @php
                                                $diffStart = $itinerary->start_date->diffInDays($today, false);
                                                $diffEnd = $itinerary->end_date->diffInDays($today, false);
                                                $members = $itinerary->group->members;
                                                $authUser = Auth::user();

                                                // Laravelで残りメンバーを整形
                                                $remainingMembers = $members->slice(3)->map(function ($user) use ($authUser) {
                                                    $link = null;

                                                    if ($user->private_group && $user->private_group->isNotEmpty() &&
                                                        $user->private_group->contains('name', $authUser->name)) {
                                                        $link = route('message.show', $user->private_group->first()->id);
                                                    } elseif ($authUser->private_group && $authUser->private_group->isNotEmpty() &&
                                                        $authUser->private_group->contains('name', $user->name)) {
                                                        $link = route('message.show', $authUser->private_group->first()->id);
                                                    }

                                                    return [
                                                        'name' => $user->name,
                                                        'avatar' => $user->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg'),
                                                        'link' => $link,
                                                    ];
                                                })->values();

                                                $itineraryId = $itinerary->id;
                                                $remainingJson = $remainingMembers->toJson();
                                            @endphp

                                            @if (($diffStart <= 0 && $diffStart >= -30) || ($diffEnd <= 0 && $diffStart >= 0))
                                                <div class="w-full bg-yellow-200 rounded-md p-4 hover:bg-yellow-300 transition shadow-lg flex flex-col justify-between">
                                                    {{-- 日付 --}}
                                                    <div class="text-sm text-gray-700 mb-2 text-center">
                                                        <span>{{ $itinerary->start_date->format('Y-m-d') }}</span> ~
                                                        <span>{{ $itinerary->end_date->format('Y-m-d') }}</span>
                                                    </div>

                                                    {{-- タイトル --}}
                                                    <a href="{{ route('itinerary.show', $itineraryId) }}" class="font-semibold text-md text-blue-900 hover:text-blue-600 truncate text-center">
                                                        {{ $itinerary->title }}
                                                    </a>

                                                    <div class="text-sm text-black text-center">
                                                        <span>Group: {{ $itinerary->group->name }}</span>
                                                    </div>

                                                    {{-- メンバー一覧 --}}
                                                    <div class="flex flex-wrap justify-center items-center gap-2 mt-4">
                                                        {{-- 最初の3人（リンクあり） --}}
                                                        @foreach ($members->take(3) as $user)
                                                            @php
                                                                $link = null;

                                                                if ($user->private_group && $user->private_group->isNotEmpty() &&
                                                                    $user->private_group->contains('name', $authUser->name)) {
                                                                    $link = route('message.show', $user->private_group->first()->id);
                                                                } elseif ($authUser->private_group && $authUser->private_group->isNotEmpty() &&
                                                                    $authUser->private_group->contains('name', $user->name)) {
                                                                    $link = route('message.show', $authUser->private_group->first()->id);
                                                                }
                                                            @endphp

                                                            <div class="text-center">
                                                                @if ($link)
                                                                    <a href="{{ $link }}" class="block w-8 h-8 rounded-full overflow-hidden bg-gray-200 mx-auto">
                                                                        <img src="{{ $user->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}"
                                                                            alt="{{ $user->name }}"
                                                                            class="w-full h-full object-cover">
                                                                    </a>
                                                                @else
                                                                    <div class="block w-8 h-8 rounded-full overflow-hidden bg-gray-200 mx-auto">
                                                                        <img src="{{ $user->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}"
                                                                            alt="{{ $user->name }}"
                                                                            class="w-full h-full object-cover">
                                                                    </div>
                                                                @endif
                                                                <p class="text-xs mt-1 truncate text-black max-w-[72px]">{{ $user->name }}</p>
                                                            </div>
                                                        @endforeach

                                                        {{-- Alpine.jsで残りのメンバーを表示 --}}
                                                        <template x-for="(user, index) in {{ $remainingJson }}" :key="index">
                                                            <div class="text-center" x-show="expandedMembers[{{ $itineraryId }}]">
                                                                <template x-if="user.link">
                                                                    <a :href="user.link" class="block w-8 h-8 rounded-full overflow-hidden bg-gray-200 mx-auto">
                                                                        <img :src="user.avatar" :alt="user.name" class="w-full h-full object-cover">
                                                                    </a>
                                                                </template>
                                                                <template x-if="!user.link">
                                                                    <div class="block w-8 h-8 rounded-full overflow-hidden bg-gray-200 mx-auto">
                                                                        <img :src="user.avatar" :alt="user.name" class="w-full h-full object-cover">
                                                                    </div>
                                                                </template>
                                                                <p class="text-xs mt-1 truncate text-black max-w-[72px]" x-text="user.name"></p>
                                                            </div>
                                                        </template>

                                                        {{-- 展開/閉じるボタン --}}
                                                        @if ($members->count() > 3)
                                                            <button
                                                                @click="expandedMembers[{{ $itineraryId }}] = !expandedMembers[{{ $itineraryId }}]"
                                                                class="text-sm text-gray-700 hover:underline"
                                                            >
                                                                <span x-show="!expandedMembers[{{ $itineraryId }}]">...+{{ $members->count() - 3 }}more</span>
                                                                <span x-show="expandedMembers[{{ $itineraryId }}]">▲ close</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                {{-- 🔶 Post Like Ranking（下に移動） --}}
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h2 class="text-center text-lg font-semibold mb-4">Post Like Ranking</h2>

                                    {{-- ランキング横並び --}}
                                    <div class="flex gap-4 overflow-x-auto pb-4">
                                        @foreach ($posts as $index => $post)
                                            <div class="min-w-[200px] bg-white rounded-lg shadow-md flex-shrink-0 overflow-hidden border border-gray-200">
                                                {{-- タイトル・順位 --}}
                                                <div class="text-center px-2 pt-2">
                                                    @if ($likeCounts[$index] == $likeCounts[0])
                                                        <div class="text-yellow-300 font-semibold text-sm">
                                                            <i class="fa-solid fa-crown"></i> 1st
                                                        </div>
                                                        <a href="{{ route('post.show', $post->id) }}"
                                                        class="block text-black hover:text-yellow-300 text-xs truncate mt-1">
                                                            {{ $post->title }}
                                                        </a>
                                                    @elseif ($likeCounts[$index] == $likeCounts[1])
                                                        <div class="text-gray-400 font-semibold text-sm">
                                                            <i class="fa-solid fa-crown"></i> 2nd
                                                        </div>
                                                        <a href="{{ route('post.show', $post->id) }}"
                                                        class="block text-black hover:text-gray-400 text-xs truncate mt-1">
                                                            {{ $post->title }}
                                                        </a>
                                                    @else
                                                        <div class="text-yellow-500 font-semibold text-sm">
                                                            <i class="fa-solid fa-crown"></i> 3rd
                                                        </div>
                                                        <a href="{{ route('post.show', $post->id) }}"
                                                        class="block text-black hover:text-yellow-500 text-xs truncate mt-1">
                                                            {{ $post->title }}
                                                        </a>
                                                    @endif
                                                </div>

                                                {{-- 画像 + ハート重ね表示 --}}
                                                <div class="relative mt-2">
                                                    <a href="{{ route('post.show', $post->id) }}">
                                                        <img src="{{ $post->image }}"
                                                            alt="{{ $post->id }}"
                                                            class="w-full h-40 object-cover hover:scale-105 transition duration-300">
                                                        <div class="absolute bottom-2 right-2 bg-white/90 rounded-full px-2 py-1 flex items-center text-sm shadow">
                                                            <i class="fa-solid fa-heart text-red-500 mr-1"></i>
                                                            <span>{{ $post->likes()->count() }}</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- View More リンク --}}
                                    <div class="mt-4 text-right text-sm text-blue-500">
                                        <a href="{{ route('post.list') }}">View Post More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


{{-- caldender js --}}
<script src="{{ asset('js/homepage_calender.js') }}"></script>

