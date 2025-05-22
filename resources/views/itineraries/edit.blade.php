<x-app-layout>
    <div class="py-12 h-[880px]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-6xl font-bold absolute left-1/2 transform -translate-x-1/2">Edit</h1>
                    </div>

                    <div class="max-w-6xl mx-auto h-full mt-8">
                        <div class="bg-white rounded-lg p-6 h-[640px] flex flex-col">
                            <form id="itineraryForm" action="#" method="POST" class="flex flex-col h-full">
                                @csrf
                                @method('PATCH')
                                {{-- header --}}
                                <div class="grid grid-cols-5 items-center border-b shrink-0">
                                    {{-- title --}}
                                    <div class="col-span-2 mb-4 me-2">
                                        <x-input-label for="title" value="Title" />
                                        <x-text-input name="title" placeholder="Please enter a title" required
                                            class="w-full" value="{{ old('title', $itinerary->title) }}" />
                                    </div>

                                    {{-- date --}}
                                    <div class="col-span-2 mb-4">
                                        {{-- label --}}
                                        <div class="grid grid-cols-5">
                                            <div class="col-span-2">
                                                <x-input-label for="start_date">Start Date</x-input-label>
                                            </div>
                                            <div class="col-span-1">
                                            </div>
                                            <div class="col-span-2">
                                                <x-input-label for="end_date">End Date</x-input-label>
                                            </div>
                                        </div>
                                        {{-- input --}}
                                        <div class="grid grid-cols-5 items-center">
                                            <div class="col-span-2">
                                                <x-text-input name="start_date" type="date" id="start_date"
                                                    onchange="updateDateRange()" value="{{ old('start_date', optional($itinerary->start_date)->format('Y-m-d')) }}" />
                                            </div>
                                            <div class="col-span-1 flex justify-center">
                                                <span class="text-lg">～</span>
                                            </div>
                                            <div class="col-span-2">
                                                <x-text-input name="end_date" type="date" id="end_date"
                                                    onchange="updateDateRange()" value="{{ old('end_date', optional($itinerary->end_date)->format('Y-m-d')) }}" />
                                            </div>
                                        </div>
                                    </div>

                                    {{-- update button --}}
                                    <div class="col-span-1 text-end me-4">
                                        <button type="submit"
                                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 focus-outline-none focus:ring-2 focus:ring-green-500">Update</button>
                                    </div>
                                </div>

                                {{-- main --}}
                                <div class=" w-full mx-auto flex-1 flex overflow-hidden">
                                    {{-- left side --}}
                                    <div id="dateFieldsContainer" class=" flex-1 py-2 overflow-y-auto border-e">
                                        {{-- create itineraries --}}
                                        {{-- total summary --}}
                                        <div id="totalSummary" class="mt-6 text-sm hidden"></div>
                                    </div>
                                    {{-- right side (Map) --}}
                                    <div class="bg-white rounded-lg flex flex-col w-1/2">
                                        <div class="flex">
                                            {{-- destination form --}}
                                        </div>
                                        <div class="relative">
                                            {{-- search bar --}}
                                            <input id="searchInput" type="text" placeholder="Search a location" class="absolute top-2 left-[62%] mt-1 transform -translate-x-1/2 w-[300px] p-2 border border-gray-300 rounded shadow z-10 bg-white" />
                                            <div id="map" class="h-[600px] w-full border"></div>

                                            {{-- search result --}}
                                            <div id="searchResultInfo" class="absolute top-16 left-1/2 transform -translate-x-1/2 bg-white p-4 rounded shadow z-10 w-[300px] hidden">
                                                <h3 id="placeName" class="font-bold text-lg"></h3>
                                                <p id="placeAddress" class="text-sm text-gray-700 my-2"></p>
                                                <button onclick="addToDestinations()" class="mt-2 bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                                    <i class="fa-solid fa-location-dot"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- java script --}}
                                @push('scripts')
                                    <script>
                                        window.initialItineraryData = @json($itineraryData);
                                    </script>
                                    <script src="{{ asset('js/itinerary-map-script.js') }}"></script>
                                    <script src="{{ asset('js/itinerary-create-script.js') }}"></script>
                                @endpush

                            </form>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
