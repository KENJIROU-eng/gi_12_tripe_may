<x-app-layout>
    <div class="py-12 h-[880px]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-6xl font-bold absolute left-1/2 transform -translate-x-1/2">Create</h1>
                    </div>

                    <div class="max-w-6xl mx-auto h-full mt-8">
                        <div class="bg-white rounded-lg p-6 h-[640px] flex flex-col">
                            <form id="itineraryForm" action="{{ route('itinerary.store') }}" method="POST" class="flex flex-col h-full">
                                @csrf

                                {{-- header --}}
                                <div class="grid grid-cols-5 items-center border-b shrink-0">
                                    {{-- title --}}
                                    <div class="col-span-2 mb-4 me-2">
                                        <x-input-label for="title" value="Title" />
                                        <x-text-input name="title" id="title" placeholder="Please enter a title" required class="w-full" />
                                    </div>

                                    {{-- date --}}
                                    <div class="col-span-2 mb-4">
                                        <div class="grid grid-cols-5">
                                            <div class="col-span-2">
                                                <x-input-label for="start_date">Start Date</x-input-label>
                                            </div>
                                            <div class="col-span-1"></div>
                                            <div class="col-span-2">
                                                <x-input-label for="end_date">End Date</x-input-label>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-5 items-center">
                                            <div class="col-span-2">
                                                <x-text-input name="start_date" type="date" id="start_date" />
                                            </div>
                                            <div class="col-span-1 flex justify-center">
                                                <span class="text-lg">～</span>
                                            </div>
                                            <div class="col-span-2">
                                                <x-text-input name="end_date" type="date" id="end_date" />
                                            </div>
                                        </div>
                                    </div>

                                    {{-- create button --}}
                                    <div class="col-span-1 text-end me-4">
                                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 focus-outline-none focus:ring-2 focus:ring-green-500">
                                            Create
                                        </button>
                                    </div>
                                </div>

                                {{-- main --}}
                                <div class="w-full mx-auto flex-1 flex overflow-hidden">
                                    {{-- left side --}}
                                    <div class="flex-1 flex flex-col border-e overflow-hidden">
                                        {{-- ここにhidden inputsをまとめて配置 --}}
                                        <input type="hidden" name="daily_distances" id="daily_distances" />
                                        <input type="hidden" name="daily_durations" id="daily_durations" />
                                        <input type="hidden" name="total_distance" id="total_distance" />
                                        <input type="hidden" name="total_duration" id="total_duration" />

                                        <input type="hidden" name="initial_place" id="initial_place" />

                                        <div id="dateFieldsContainer" class="flex-1 py-2 overflow-y-auto">
                                            <!-- 目的地入力欄がここに -->
                                        </div>
                                        <div id="totalSummary" class="mt-2 text-sm hidden flex-shrink-0"></div>
                                    </div>

                                    {{-- right side (Map only) --}}
                                    <div class="bg-white rounded-lg flex flex-col w-1/2">
                                        <div class="relative">
                                            <div id="map" class="h-[600px] w-full border"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- JavaScript --}}
                                @push('scripts')
                                    <script src="{{ asset('js/itineraries/map.js') }}"></script>
                                    <script src="{{ asset('js/itineraries/create.js') }}"></script>
                                    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap&loading=async" async defer></script>
                                @endpush

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
