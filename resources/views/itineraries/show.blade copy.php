<x-app-layout>
    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white shadow-md dark:bg-gray-800 overflow-hidden sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    {{-- タイトルとグループ表示 --}}
                    <div class="relative my-5">
                        {{-- タイトル --}}
                        <div class="w-full text-center">
                            <h1 class="text-4xl sm:text-6xl font-bold">
                                Details
                            </h1>
                        </div>

                        {{-- グループ情報 --}}
                        @if ($itinerary->group_id != null)
                            <div
                                x-data="{ open: false }"
                                class="-mt-2 flex flex-col items-center md:absolute md:top-0 md:right-0 md:items-end space-y-2"
                            >
                                {{-- グループ名 --}}
                                <div class="text-lg text-blue-500">
                                    <a href="{{ route('message.show', $itinerary->group_id) }}">
                                        {{ $itinerary->group->name }} <i class="fa-regular fa-comment-dots"></i>
                                    </a>
                                </div>

                                {{-- アイコン --}}
                                <button @click="open = !open" class="flex -space-x-4">
                                    @foreach ($itinerary->group->users->take(3) as $user)
                                        <img
                                            src="{{ $user->avatar_url ?? asset('images/user.png') }}"
                                            class="w-9 h-9 rounded-full border-2 border-white hover:z-10"
                                            alt="{{ $user->name }}"
                                        >
                                    @endforeach

                                    @if ($itinerary->group->users->count() > 3)
                                        <div class="w-8 h-8 rounded-full bg-gray-300 text-xs text-white flex items-center justify-center border-2 border-white">
                                            +{{ $itinerary->group->users->count() - 3 }}
                                        </div>
                                    @endif
                                </button>

                                {{-- メンバー一覧 --}}
                                <div
                                    x-show="open"
                                    @click.away="open = false"
                                    class="mt-2 w-64 bg-white border rounded-lg shadow-lg z-50"
                                >
                                    <div class="p-4">
                                        <h2 class="text-sm font-semibold text-gray-600 mb-2">Group Member</h2>
                                        <ul class="space-y-2 max-h-60 overflow-y-auto">
                                            @foreach ($itinerary->group->users as $user)
                                                <li class="flex items-center space-x-3">
                                                    <a href="{{ route('profile.show', $user->id) }}">
                                                        <img
                                                            src="{{ $user->avatar_url ?? asset('images/user.png') }}"
                                                            class="w-8 h-8 rounded-full"
                                                            alt="{{ $user->name }}"
                                                        >
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
                        @endif
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                        {{-- 左：行程詳細 --}}
                        <div class="lg:col-span-2 flex flex-col border rounded-lg shadow-sm overflow-hidden">
                            <div class="bg-white dark:bg-gray-900 p-4 h-auto lg:h-[715px] flex flex-col">
                                {{-- Header --}}
                                <div class="grid grid-cols-5 items-center pb-2">
                                    <div class="col-span-4">
                                        <p class="text-gray-500">Title</p>
                                        <p class="font-bold">{{ $itinerary->title }}</p>
                                        <p class="text-gray-500">Date</p>
                                        <p class="font-bold">
                                            {{ \Carbon\Carbon::parse($itinerary->start_date)->format('M. d, Y') }}
                                            ～ {{ \Carbon\Carbon::parse($itinerary->end_date)->format('M. d, Y') }}
                                        </p>
                                    </div>
                                    <div class="col-span-1 flex items-center ms-4 gap-4">
                                        <a href="{{ route('itinerary.edit', $itinerary->id) }}" title="Edit">
                                            <i class="fa-solid fa-pen text-yellow-300"></i>
                                        </a>
                                        @include('itineraries.modals.delete', ['itinerary' => $itinerary])
                                    </div>
                                </div>

                                {{-- Body --}}
                                <div class="overflow-y-auto flex-1 mt-4 h-[550px]">
                                    @php
                                        $grandTotalDistance = 0;
                                        $grandTotalDurationSeconds = 0;
                                    @endphp
                                    @foreach ($itinerary->dateItineraries as $dateItinerary)
                                        <h3 class="font-semibold mt-6 mb-2 text-blue-600 border-b pb-1 text-lg">
                                            {{ \Carbon\Carbon::parse($dateItinerary->date)->format('M. d, Y') }}
                                        </h3>
                                        <ul>
                                            @php
                                                $dailyTotalDistance = 0;
                                                $dailyDurationSeconds = 0;
                                            @endphp
                                            @foreach ($dateItinerary->mapItineraries as $map)
                                                <li class="flex justify-between items-center py-1 border-b border-dashed border-gray-200">
                                                    <span class="ml-4 flex items-center gap-2">
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
                                            @endforeach
                                            @php
                                                $grandTotalDistance += $dailyTotalDistance;
                                                $grandTotalDurationSeconds += $dailyDurationSeconds;
                                            @endphp
                                        </ul>
                                    @endforeach
                                </div>

                                <div class="mt-4">
                                    <p class="text-blue-800 text-md text-end flex justify-end items-center gap-2">
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
                                <div class="border rounded-lg shadow-sm bg-white dark:bg-gray-900 p-4 h-auto md:h-[300px]" id="goDutch-container">
                                    @include('goDutch.index', ['total_Pay' => $total_Pay, 'total_getPay' => $total_getPay])
                                </div>
                                <div class="border rounded-lg shadow-sm bg-white dark:bg-gray-900 p-4 h-auto md:h-[300px]" id="belongings-container">
                                    @include('belongings.index', ['all_belongings' => $all_belongings])
                                </div>
                            </div>
                            <div id="map" class="w-full border rounded-lg shadow-sm bg-white dark:bg-gray-900 h-[300px] sm:h-[400px]">
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
                        <script src="{{ asset('js/itineraries/show.js') }}"></script>
                        <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initShowMap&loading=async" async defer></script>
                    @endpush
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
