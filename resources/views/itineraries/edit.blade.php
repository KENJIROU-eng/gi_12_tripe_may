<x-app-layout>
    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="text-black dark:text-gray-100">

                    {{-- タイトル --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-4xl md:text-6xl font-bold absolute left-1/2 transform -translate-x-1/2">Edit</h1>
                    </div>

                    <div class="max-w-6xl mx-auto">
                        <div class="bg-white dark:bg-gray-900 rounded-lg p-6 flex flex-col">
                            <form id="itineraryForm" method="POST" action="{{ route('itinerary.update', $itinerary->id) }}" class="flex flex-col h-full">
                                @csrf
                                @method('PUT')

                                {{-- Header: タイトル・日付・グループ・更新ボタン --}}
                                <div class="grid grid-cols-1 md:grid-cols-10 gap-4 items-end border-b pb-4">

                                    {{-- タイトル --}}
                                    <div class="md:col-span-4">
                                        <x-input-label for="title" value="Title" />
                                        <x-text-input
                                            id="title"
                                            name="title"
                                            placeholder="Please enter a title"
                                            required
                                            class="w-full"
                                            value="{{ old('title', $itinerary->title) }}"
                                        />
                                        @error('title')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- 開始日と終了日 --}}
                                    <div class="md:col-span-3">
                                        <div class="grid grid-cols-9 gap-1 mb-1 text-sm">
                                            <div class="col-span-4">
                                                <x-input-label for="start_date" value="Start Date" />
                                            </div>
                                            <div class="col-span-1 text-center">〜</div>
                                            <div class="col-span-4">
                                                <x-input-label for="end_date" value="End Date" />
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-9 items-center gap-1">
                                            <div class="col-span-4">
                                                <x-text-input
                                                    id="start_date"
                                                    name="start_date"
                                                    type="date"
                                                    class="w-full"
                                                    value="{{ old('start_date', $itinerary->start_date?->format('Y-m-d')) }}"
                                                />
                                                @error('start_date')
                                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="col-span-1 text-center text-lg">〜</div>
                                            <div class="col-span-4">
                                                <x-text-input
                                                    id="end_date"
                                                    name="end_date"
                                                    type="date"
                                                    class="w-full"
                                                    value="{{ old('end_date', $itinerary->end_date?->format('Y-m-d')) }}"
                                                />
                                                @error('end_date')
                                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- グループ選択 --}}
                                    <div class="md:col-span-2">
                                        <x-input-label for="group_id" value="Group" />
                                        <select name="group_id" id="group_id" class="w-full border-gray-300 rounded-md">
                                            <option value="" {{ is_null($itinerary->group_id) ? 'selected' : '' }}>No Group</option>
                                            @foreach ($allGroups as $group)
                                                <option value="{{ $group->id }}" {{ $group->id == $itinerary->group_id ? 'selected' : '' }}>
                                                    {{ $group->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('group_id')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- 更新ボタン --}}
                                    <div class="md:col-span-1 text-end">
                                        <button type="submit"
                                            class="w-full md:w-auto mt-6 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400">
                                            Update
                                        </button>
                                    </div>
                                </div>

                                {{-- Mainコンテンツ --}}
                                <div class="flex-1 flex flex-col md:flex-row mt-4 gap-4">
                                    {{-- 左: 目的地入力欄 --}}
                                    <div class="flex-1 flex flex-col border-b md:border-b-0 md:border-e">
                                        {{-- Hiddenフィールド --}}
                                        <input type="hidden" name="daily_distances" id="daily_distances" />
                                        <input type="hidden" name="daily_durations" id="daily_durations" />
                                        <input type="hidden" name="total_distance" id="total_distance" />
                                        <input type="hidden" name="total_duration" id="total_duration" />
                                        <input type="hidden" name="initial_place" id="initial_place" />

                                        <div id="dateFieldsContainer" class="py-2 overflow-y-auto max-h-[560px]"></div>

                                        <div id="totalSummary" class="mt-2 text-sm text-end text-gray-600 hidden px-2"></div>
                                    </div>

                                    {{-- 右: Google Map --}}
                                    <div class="w-full md:w-1/2 bg-white rounded-lg">
                                        <div id="map" class="h-64 md:h-[600px] w-full border"></div>
                                    </div>
                                </div>

                                {{-- JSスクリプト --}}
                                @push('scripts')
                                    <script>
                                        window.existingData = @json($itineraryData['destinations'] ?? []);
                                        window.originalGroupId = "{{ $itinerary->group_id ?? '' }}";
                                    </script>
                                    <script src="{{ asset('js/itineraries/map.js') }}"></script>
                                    <script src="{{ asset('js/itineraries/edit.js') }}"></script>
                                    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap&loading=async" async defer></script>
                                @endpush
                            </form>

                            {{-- グループ変更確認モーダル --}}
                            <div id="groupModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center">
                                <div class="bg-white rounded shadow-lg p-6 w-80">
                                    <h2 class="text-lg font-bold mb-2 text-red-600">
                                        <i class="fa-solid fa-triangle-exclamation"></i> Caution
                                    </h2>
                                    <p class="mb-4 text-sm text-gray-700">
                                        If you change groups, your GoDutch data will be deleted. Are you sure?
                                    </p>
                                    <div class="flex justify-end gap-2">
                                        <button id="cancelGroupChange" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                                        <button id="confirmGroupChange" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">OK</button>
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
