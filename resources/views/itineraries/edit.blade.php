<x-app-layout>
    <div class="py-8 min-h-screen md:h-[880px]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="text-black dark:text-gray-100">
                    {{-- タイトル --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-3xl md:text-6xl font-bold absolute left-1/2 transform -translate-x-1/2">Edit</h1>
                    </div>

                    <div class="max-w-6xl mx-auto">
                        <div class="bg-white rounded-lg p-6 flex flex-col h-auto">
                            <form id="itineraryForm" action="{{ route('itinerary.update', $itinerary->id) }}" method="POST" class="flex flex-col h-full">
                                @csrf
                                @method('PUT')

                                {{-- header --}}
                                <div class="grid grid-cols-1 md:grid-cols-10 gap-4 items-center border-b shrink-0 pb-4">
                                    {{-- title --}}
                                    <div class="md:col-span-4">
                                        <x-input-label for="title" value="Title" />
                                        <x-text-input
                                            name="title"
                                            id="title"
                                            placeholder="Please enter a title"
                                            required
                                            class="w-full"
                                            value="{{ old('title', $itinerary->title) }}"
                                        />
                                    </div>

                                    {{-- date --}}
                                    <div class="md:col-span-3">
                                        <div class="grid grid-cols-9">
                                            <div class="col-span-4">
                                                <x-input-label for="start_date">Start Date</x-input-label>
                                            </div>
                                            <div class="col-span-1"></div>
                                            <div class="col-span-4">
                                                <x-input-label for="end_date">End Date</x-input-label>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-9 items-center">
                                            <div class="col-span-4">
                                                <x-text-input
                                                    name="start_date"
                                                    type="date"
                                                    id="start_date"
                                                    class="w-full"
                                                    value="{{ old('start_date', $itinerary->start_date?->format('Y-m-d')) }}"
                                                />
                                            </div>
                                            <div class="col-span-1 text-center">
                                                <span class="text-lg">～</span>
                                            </div>
                                            <div class="col-span-4">
                                                <x-text-input
                                                    name="end_date"
                                                    type="date"
                                                    id="end_date"
                                                    class="w-full"
                                                    value="{{ old('end_date', $itinerary->end_date?->format('Y-m-d')) }}"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    {{-- group select --}}
                                    <div class="md:col-span-2">
                                        <x-input-label for="group_id">Group</x-input-label>
                                        <select name="group_id" id="group_id" class="block w-full border border-gray-300 rounded-md">
                                            <option value="" {{ is_null($itinerary->group_id) ? 'selected' : '' }}>No Group</option>
                                            @foreach ($allGroups as $group)
                                                <option value="{{ $group->id }}" {{ $group->id == $itinerary->group_id ? 'selected' : '' }}>
                                                    {{ $group->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>



                                    {{-- update button --}}
                                    <div class="md:col-span-1 text-end">
                                        <button type="submit"
                                            class="w-full md:w-auto px-4 mt-5 py-2 bg-green-500 text-white rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            Update
                                        </button>
                                    </div>
                                </div>

                                {{-- main --}}
                                <div class="w-full mx-auto flex-1 flex flex-col md:flex-row mt-4 gap-4">
                                    {{-- left side --}}
                                    <div class="flex-1 flex flex-col border-b md:border-b-0 md:border-e overflow-hidden">
                                        <input type="hidden" name="daily_distances" id="daily_distances" />
                                        <input type="hidden" name="daily_durations" id="daily_durations" />
                                        <input type="hidden" name="total_distance" id="total_distance" />
                                        <input type="hidden" name="total_duration" id="total_duration" />
                                        <input type="hidden" name="initial_place" id="initial_place" />

                                        <div id="dateFieldsContainer" class="flex-1 py-2 overflow-y-auto max-h-[560px]">
                                            <!-- 目的地入力欄 -->
                                        </div>

                                        <div id="totalSummary" class="mt-2 text-md text-end hidden flex-shrink-0 px-2"></div>
                                    </div>

                                    {{-- right side (map) --}}
                                    <div class="bg-white rounded-lg flex flex-col w-full md:w-1/2">
                                        <div class="relative">
                                            <div id="map" class="h-64 md:h-[600px] w-full border"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- JavaScript --}}
                                @push('scripts')
                                    <script>
                                        window.existingData = @json($itineraryData['destinations'] ?? []);
                                    </script>
                                    <script src="{{ asset('js/itineraries/map.js') }}"></script>
                                    <script src="{{ asset('js/itineraries/edit.js') }}"></script>
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
