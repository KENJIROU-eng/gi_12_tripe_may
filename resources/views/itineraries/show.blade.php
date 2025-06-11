<x-app-layout>
    <div class="py-12 h-[880px]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white shadow-md dark:bg-gray-800 overflow-hidden sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-6xl font-bold absolute left-1/2 transform -translate-x-1/2">
                            Details
                        </h1>

                        {{-- group member --}}
                        <div class="absolute right-0 flex items-center space-x-2">
                            <p>{{ $groupName }}</p>
                            @foreach ($displayMembers as $member)
                                <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-300 bg-gray-100 flex items-center justify-center text-gray-400">
                                    @if ($member->avatar_url)
                                        <a href="{{ route('profile.show', $member->user_id) }}">
                                            <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                        </a>
                                    @else
                                        <a href="{{ route('profile.show', $member->id) }}"><i class="fa-regular fa-circle-user fa-lg"></i></a>
                                    @endif
                                </div>
                            @endforeach

                            @if ($remainingCount > 0)
                                {{-- go to group --}}
                                <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-lg border border-gray-300">
                                    <a href="#">{{ $remainingCount }}</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="w-full mx-auto flex-1 flex overflow-hidden">
                        {{-- left contents 1\3 --}}
                        {{-- itinerary --}}
                        <div class="flex-1 flex flex-col border overflow-hidden w-2/5">
                            <div class="bg-white shadow-md rounded-lg p-4 max-w-lg h-[600px] flex flex-col">
                                {{-- Header --}}
                                <div class="grid grid-cols-5 items-center border-b">
                                    <div class="col-span-4">
                                        <p class="text-gray-500">Title</p>
                                        <p class="font-bold">{{ $itinerary->title }}</p>
                                        <p class="text-gray-500">Date</p>
                                        <p class="font-bold">
                                            {{ \Carbon\Carbon::parse($itinerary->start_date)->format('M. d, Y') }}
                                            ～
                                            {{ \Carbon\Carbon::parse($itinerary->end_date)->format('M. d, Y') }}
                                        </p>
                                    </div>
                                    <div class="col-span-1 flex items-center ms-4 gap-4">
                                        {{-- Edit button --}}
                                        <a href="{{ route('itinerary.edit', $itinerary->id) }}">
                                            <i class="fa-solid fa-pen text-yellow-300"></i>
                                        </a>
                                        {{-- Delete modal --}}
                                        @include('itineraries.modals.delete', ['itinerary' => $itinerary])
                                    </div>
                                </div>
                                {{-- Body --}}
                                <div class="max-w-lg max-h-[450px] flex flex-col mt-4">
                                    @php
                                        $grandTotalDistance = 0;
                                        $grandTotalDurationSeconds = 0;
                                    @endphp
                                    <div class="overflow-y-auto flex-1">
                                        @foreach ($itinerary->dateItineraries as $dateItinerary)
                                            <h3 class="font-bold mt-4 text-lg">
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
                                                            {{-- travel_mode アイコン --}}
                                                            @php
                                                                $mode = strtoupper($map->travel_mode ?? 'DRIVING');
                                                                $modeIconMap = [
                                                                    'DRIVING'     => ['icon' => 'fa-car-side',       'color' => 'text-blue-500'],
                                                                    'MOTORCYCLE'  => ['icon' => 'fa-motorcycle',     'color' => 'text-green-500'],
                                                                    'WALKING'     => ['icon' => 'fa-person-walking', 'color' => 'text-red-500'],
                                                                    'TRANSIT'     => ['icon' => 'fa-van-shuttle',    'color' => 'text-yellow-500'],
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
                                    {{-- Total summary --}}
                                    <div class="mt-4">
                                        <p class="text-blue-800 text-md text-end">
                                            Total Distance: {{ number_format($grandTotalDistance, 1) }} km /
                                            Total Duration: {{ gmdate('G\h i\m', $grandTotalDurationSeconds) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- right contents 2/3 --}}
                        <div class="flex flex-col w-3/5 overflow-hidden">
                            <div class="w-full mx-auto flex flex-1 overflow-hidden">
                                {{-- bill 1/2 --}}
                                <div class="flex flex-col w-1/2 border h-full p-2">
                                </div>
                                {{-- belonging list 1/2 --}}
                                <div class="flex flex-col w-1/2 border h-full p-2">
                                    @include('belongings.index', ['all_belongings' => $all_belongings])
                                </div>
                            </div>
                            {{-- map 1/1 --}}
                            <div id="map" class="w-full border h-1/2">
                                map
                            </div>
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
        </div>
    </div>
</x-app-layout>
