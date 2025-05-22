<x-app-layout>
    <div class="py-12 h-[880px]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white shadow-md dark:bg-gray-800 overflow-hidden sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-6xl font-bold absolute left-1/2 transform -translate-x-1/2">Details</h1>

                    </div>

                    {{-- contents --}}
                    <div class="max-w-5xl h-full mt-8 flex justify-start">
                        <div class="bg-white shadow-md rounded-lg p-4 max-w-lg h-[600px] flex flex-col">
                            {{-- header --}}
                            <div class="grid grid-cols-5 items-center border-b">
                                <div class="col-span-4">
                                    <p class="text-gray-500">Title</p>
                                    <p class="font-bold">{{ $itinerary->title }}</p>
                                    <p class="text-gray-500">Date</p>
                                    <p class="font-bold">{{ \Carbon\Carbon::parse($itinerary->start_date)->format('M. d, Y') }} ～ {{ \Carbon\Carbon::parse($itinerary->end_date)->format('M. d, Y') }}</p>
                                </div>
                                <div class="col-span-1 flex items-center ms-4 gap-4">
                                    {{-- edit button --}}
                                    <a href="{{ route('itinerary.edit', $itinerary->id) }}">
                                        <i class="fa-solid fa-pen text-yellow-300"></i>
                                    </a>
                                    {{-- delete button(modal) --}}
                                    @include('itineraries.modals.delete', ['itinerary' => $itinerary])
                                </div>
                            </div>
                            {{-- body --}}
                            <div class="max-w-lg max-h-[450px] flex flex-col mt-4">

                                @php
                                    $grandTotalDistance = 0;
                                    $grandTotalDurationSeconds = 0;
                                @endphp

                                <div class="overflow-y-auto flex-1">
                                    @foreach ($itinerary->dateItineraries as $dateItinerary)
                                        <h3 class="font-bold mt-4 text-lg">{{ \Carbon\Carbon::parse($dateItinerary->date)->format('M. d, Y') }}</h3>
                                        <ul>
                                            @php
                                                $dailyTotalDistance = 0;
                                                $dailyDurationSeconds = 0;
                                            @endphp
                                            @foreach ($dateItinerary->mapItineraries as $map)
                                                <li class="flex justify-between items-center py-1 border-b border-dashed border-gray-200">
                                                    <span class="ml-4">
                                                        {{ $map->place_name ?? $map->destination }}
                                                    </span>
                                                    @if ($map->distance_km !== null && $map->duration_text !== null)
                                                        <span class="text-gray-500 text-sm whitespace-nowrap">
                                                            {{ number_format($map->distance_km, 1) }} km / {{ $map->duration_text }}
                                                        </span>
                                                        @php
                                                            $dailyTotalDistance += $map->distance_km;
                                                            if (preg_match_all('/(\d+)\s*(hour|minute)/', $map->duration_text, $matches, PREG_SET_ORDER)) {
                                                                foreach ($matches as $match) {
                                                                    if ($match[2] === 'hour') {
                                                                        $dailyDurationSeconds += intval($match[1]) * 3600;
                                                                    } elseif ($match[2] === 'minute') {
                                                                        $dailyDurationSeconds += intval($match[1]) * 60;
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

                                {{-- grand total distance & duration --}}
                                <div class="mt-4">
                                    <p class="text-blue-700 text-lg text-end">
                                        Total Distance: {{ number_format($grandTotalDistance, 1) }} km / Total duration: {{ gmdate('G\h i\m', $grandTotalDurationSeconds) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
