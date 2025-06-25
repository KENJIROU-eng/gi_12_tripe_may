<x-app-layout>
    <div class="py-4 min-h-screen bg-cover bg-center" style="background-image: url('https://res.cloudinary.com/dpwrycc89/image/upload/v1750757614/pexels-jplenio-1133505_ijwxpn.jpg');">
        <div class="flex flex-col lg:flex-row gap-4 max-w-screen-xl mx-auto px-4">
            {{-- 左：旅程表 --}}
            <div class="w-full lg:w-3/4 flex flex-col gap-4 order-2 lg:order-none">
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4">
                    {{-- header表示 --}}
                    <div class="relative flex flex-col md:flex-row items-center justify-between mb-6">
                        {{-- Back ボタン（左） --}}
                        <div class="order-1 md:order-none self-start md:self-center z-10">
                            <a href="{{ route('itinerary.index') }}" class="inline-flex items-center text-sm text-blue-500 hover:underline">
                                <i class="fa-solid fa-arrow-left mr-1"></i> Back
                            </a>
                        </div>

                        @foreach ($itinerary->bills as $bill)
                            @foreach($bill->billUser as $user)
                                <p>{{ $user->id }}</p>
                            @endforeach
                        @endforeach

                        {{-- タイトル（中央固定） --}}
                        <div class="order-0 w-full text-center md:absolute md:left-1/2 md:transform md:-translate-x-1/2">
                            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 dark:text-gray-100 animate-fadeIn">
                                <i class="fa-solid fa-d"></i>
                                <i class="fa-solid fa-e"></i>
                                <i class="fa-solid fa-t"></i>
                                <i class="fa-solid fa-a"></i>
                                <i class="fa-solid fa-i"></i>
                                <i class="fa-solid fa-l"></i>
                                <i class="fa-solid fa-s"></i>
                            </h1>
                        </div>

                        {{-- Group（右） --}}
                        @if ($itinerary->group_id)
                            <div class="order-2 md:order-none self-end md:self-center z-10">
                                <div x-data="{ open: false }" class="flex flex-col items-end space-y-1">
                                    {{-- グループ名 --}}
                                    <div class="text-blue-500 text-sm md:text-base">
                                        <a href="{{ route('message.show', $itinerary->group_id) }}">
                                            {{ Str::limit($itinerary->group->name, 20) }}
                                            <i class="fa-regular fa-comment-dots"></i>
                                        </a>
                                    </div>
                                    {{-- アバターアイコン --}}
                                    <button @click="open = !open" class="flex -space-x-3">
                                        @foreach ($itinerary->group->users->take(3) as $user)
                                            <img src="{{ $user->avatar_url ?? asset('images/user.png') }}"
                                                class="w-8 h-8 rounded-full border-2 border-white hover:z-10"
                                                alt="{{ $user->name }}">
                                        @endforeach
                                        @if ($itinerary->group->users->count() > 3)
                                            <div class="w-8 h-8 rounded-full bg-gray-300 text-xs text-white flex items-center justify-center border-2 border-white">
                                                +{{ $itinerary->group->users->count() - 3 }}
                                            </div>
                                        @endif
                                    </button>
                                    {{-- メンバー一覧 --}}
                                    <div x-show="open" @click.away="open = false"
                                        class="mt-2 w-64 bg-white border rounded-lg shadow-lg z-50">
                                        <div class="p-4">
                                            <h2 class="text-sm font-semibold text-gray-600 mb-2">Group Members</h2>
                                            <ul class="space-y-2 max-h-60 overflow-y-auto">
                                                @foreach ($itinerary->group->users as $user)
                                                    <li class="flex items-center space-x-3">
                                                        <a href="{{ route('profile.show', $user->id) }}">
                                                            <img src="{{ $user->avatar_url ?? asset('images/user.png') }}"
                                                                class="w-8 h-8 rounded-full" alt="{{ $user->name }}">
                                                        </a>
                                                        <a href="{{ route('profile.show', $user->id) }}">
                                                            <p class="text-sm">{{ $user->name }}</p>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- メイングリッド --}}
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                        {{-- 左：行程詳細 --}}
                        <div class="lg:col-span-2 flex flex-col border rounded-lg shadow-sm overflow-hidden">
                            <div class="bg-white dark:bg-gray-900 p-4 h-auto lg:h-[830px] flex flex-col">
                                {{-- Header --}}
                                <div class="items-center pb-2">
                                    <div class="col-span-4">
                                        <p class="text-gray-500">Title</p>
                                        <p class="font-bold break-words max-w-full overflow-hidden">{{ $itinerary->title }}</p>
                                        <p class="text-gray-500">Date</p>
                                        <p class="font-bold">
                                            {{ \Carbon\Carbon::parse($itinerary->start_date)->format('M. d, Y') }}
                                            ～ {{ \Carbon\Carbon::parse($itinerary->end_date)->format('M. d, Y') }}
                                        </p>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2 mt-2 sm:mt-0">
                                        {{-- View Google Map --}}
                                        <a id="shareMapBtn" href="#" target="_blank" onclick="event.preventDefault(); openShareMapLink();"
                                        class="text-blue-500 px-1 py-1 hidden sm:inline-flex items-center" title="View Google Map">
                                            <i class="fa-solid fa-map-location-dot mr-1"></i> View Google Map
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('itinerary.edit', $itinerary->id) }}"
                                        class="text-yellow-500 px-1 py-1 inline-flex items-center" title="Edit">
                                            <i class="fa-solid fa-pen text-yellow-300 mr-1"></i> Edit
                                        </a>

                                        {{-- Delete --}}
                                        <span class="flex items-center text-red-500">
                                            @include('itineraries.modals.delete', ['itinerary' => $itinerary])
                                            &nbsp;Delete
                                        </span>
                                    </div>
                                </div>
                                {{-- Body --}}
                                <div class="overflow-y-auto flex-1 my-4 h-[80vh] sm:max-h-[666px] scrollable">
                                    @php
                                        $grandTotalDistance = 0;
                                        $grandTotalDurationSeconds = 0;
                                        $isFirstOverall = true;
                                    @endphp
                                    @foreach ($itinerary->dateItineraries as $dateItinerary)
                                        <div class="flex items-center justify-between mt-2 mb-2 border-b pb-1">
                                            <h3 class="font-semibold text-blue-600 text-lg">
                                                {{ \Carbon\Carbon::parse($dateItinerary->date)->format('M. d, Y') }}
                                            </h3>
                                            <div id="weatherContainer-{{ \Carbon\Carbon::parse($dateItinerary->date)->format('Ymd') }}" class="flex items-center gap-2 text-sm text-gray-700 min-h-[24px]">
                                            </div>
                                        </div>
                                        <ul>
                                            @php
                                                $dailyTotalDistance = 0;
                                                $dailyDurationSeconds = 0;
                                            @endphp

                                        @forelse ($dateItinerary->mapItineraries as $map)
                                            <li class="flex justify-between items-center py-1 border-b border-dashed border-gray-200">
                                                <span class="ml-4 flex items-center gap-2">
                                                    @if ($isFirstOverall)
                                                        <i class="fa-solid fa-flag-checkered text-blue-600"></i>
                                                        <span class="font-semibold text-blue-600">{{ $map->place_name ?? $map->destination }}</span>
                                                        @php $isFirstOverall = false; @endphp
                                                    @else
                                                        @php
                                                            $mode = strtoupper($map->travel_mode ?? 'DRIVING');
                                                            $modeIconMap = [
                                                                'DRIVING' => ['icon' => 'fa-car-side', 'color' => 'text-blue-500'],
                                                                'MOTORCYCLE' => ['icon' => 'fa-motorcycle', 'color' => 'text-green-500'],
                                                                'WALKING' => ['icon' => 'fa-person-walking', 'color' => 'text-red-500'],
                                                                'TRANSIT' => ['icon' => 'fa-van-shuttle', 'color' => 'text-yellow-500'],
                                                            ];
                                                            $modeData = $modeIconMap[$mode] ?? ['icon' => 'fa-location-dot', 'color' => 'text-gray-400'];
                                                        @endphp
                                                        <i class="fa-solid {{ $modeData['icon'] }} {{ $modeData['color'] }}"></i>
                                                        {{ $map->place_name ?? $map->destination }}
                                                    @endif
                                                </span>

                                                @if (!is_null($map->distance_km) && !is_null($map->duration_text))
                                                    <span class="text-gray-500 text-sm whitespace-nowrap">
                                                        {{ number_format($map->distance_km, 1) }} km / {{ $map->duration_text }}
                                                    </span>

                                                    @php
                                                        $dailyTotalDistance += $map->distance_km;
                                                        if (preg_match_all('/(\d+)\s*(hour|hours|minute|min|mins)/i', $map->duration_text, $matches, PREG_SET_ORDER)) {
                                                            foreach ($matches as $match) {
                                                                switch ($match[2]) {
                                                                    case 'hour':
                                                                    case 'hours':
                                                                        $dailyDurationSeconds += intval($match[1]) * 3600;
                                                                        break;
                                                                    case 'minute':
                                                                    case 'min':
                                                                    case 'mins':
                                                                        $dailyDurationSeconds += intval($match[1]) * 60;
                                                                        break;
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                @endif
                                            </li>
                                        @empty
                                            <li class="text-sm text-gray-500 italic px-2 py-1">
                                                There is no destination on this day.
                                            </li>
                                        @endforelse
                                            @php
                                                $grandTotalDistance += $dailyTotalDistance;
                                                $grandTotalDurationSeconds += $dailyDurationSeconds;
                                            @endphp
                                        </ul>
                                    @endforeach
                                </div>
                                <div class="">
                                    <p id="totalSummary" class="text-blue-800 text-md text-end flex justify-end items-center gap-2 hidden">
                                        <i class="fa-solid fa-route text-blue-600"></i>
                                        Total Distance: {{ number_format($grandTotalDistance, 1) }} km /
                                        Total Duration: {{ gmdate('G\h i\m', $grandTotalDurationSeconds) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        {{-- 右：割り勘／持ち物／マップ --}}
                        <div class="lg:col-span-3 flex flex-col gap-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="border rounded-lg shadow-sm bg-white dark:bg-gray-900 p-4 h-auto md:h-[415px]" id="goDutch-container">
                                    @include('goDutch.index', ['total_Pay' => $total_Pay, 'total_getPay' => $total_getPay])
                                </div>
                                <div class="border rounded-lg shadow-sm bg-white dark:bg-gray-900 p-4 h-auto md:h-[415px]" id="belongings-container">
                                    @include('belongings.index', ['all_belongings' => $all_belongings])
                                </div>
                            </div>
                            <div id="map" class="w-full h-[300px] sm:h-[400px] border rounded-lg shadow-sm bg-white dark:bg-gray-900">
                                map
                            </div>
                        </div>
                    </div>
                    {{-- Scripts --}}
                    @push('scripts')
                        <script>
                            window.existingData = @json($itineraryData['destinations'] ?? []);
                            console.log('existingData:', existingData);
                        </script>
                        <script>
                            const weatherApiKey = @json(config('services.weatherapi.key'));
                        </script>
                        <script src="{{ asset('js/itineraries/show.js') }}"></script>
                        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initShowMap" async defer></script>
                    @endpush
                </div>
            </div>

            {{-- 右：ルート表示 --}}
            <div class="w-full lg:w-1/5 max-w-sm border rounded-lg shadow-md bg-white dark:bg-gray-800 p-4 h-fit order-3 lg:order-none">
                <h2 class="text-lg font-semibold text-blue-600 mb-2">Route Steps</h2>
                <ul id="route-steps" class="space-y-2 text-sm text-gray-700 dark:text-gray-200 overflow-y-auto max-h-[878px]">
                    {{-- JavaScriptでステップ追加 --}}
                </ul>
            </div>
        </div>
    </div>

    {{-- 簡易アニメーションクラス --}}
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</x-app-layout>
