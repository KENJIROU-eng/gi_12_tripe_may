<x-app-layout class="h-screen flex flex-col overflow-hidden">
    <div class="min-h-screen bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('https://res.cloudinary.com/dpwrycc89/image/upload/v1750757614/pexels-jplenio-1133505_ijwxpn.jpg');">
        <div class="pt-8 flex-1 overflow-y-auto flex flex-col lg:flex-row gap-4 max-w-screen-3xl mx-auto px-4 pb-24 md:pb-0">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4 mx-auto w-full max-w-6xl">
                {{-- タイトル --}}
                <div class="flex flex-col md:flex-row items-center justify-between text-center mb-10 gap-2 md:gap-0 relative">
                    {{-- 戻るボタン --}}
                        <div class="order-1 md:order-1">
                            <a href="{{ route('itinerary.show', $itinerary->id) }}" class="inline-flex items-center text-sm text-blue-500 hover:underline">
                                <i class="fa-solid fa-arrow-left mr-1"></i> Back
                            </a>
                        </div>
                    <h1 class="order-2 md:order-2 text-3xl sm:text-4xl md:text-6xl font-bold text-gray-800 dark:text-gray-100 animate-fadeIn">
                        <i class="fa-solid fa-e"></i>
                        <i class="fa-solid fa-d"></i>
                        <i class="fa-solid fa-i"></i>
                        <i class="fa-solid fa-t"></i>
                    </h1>
                </div>

                <form id="itineraryForm" method="POST" action="{{ route('itinerary.update', $itinerary->id) }}" class="flex flex-col gap-6">
                    @csrf
                    @method('PUT')

                    {{-- 上部フォーム（タイトル・日付・グループ・更新ボタン） --}}
                    <div class="grid grid-cols-1 md:grid-cols-10 gap-6">

                        {{-- タイトル --}}
                        <div class="md:col-span-4">
                            <x-input-label for="title">
                                <span class="text-red-500 ml-1">*</span>Title
                            </x-input-label>
                            <x-text-input id="title" name="title" placeholder="Enter a title" required maxlength="100" class="w-full" value="{{ old('title', $itinerary->title) }}" />
                            <div id="titleCharCount" class="right-2 top-2 text-sm text-gray-400">
                                0 / 100
                            </div>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 日付 --}}
                        <div class="md:col-span-3">
                            <div class="grid grid-cols-9 gap-1 text-sm">
                                <div class="col-span-4">
                                    <x-input-label for="start_date">
                                        <span class="text-red-500 ml-1">*</span>Start Date
                                    </x-input-label>
                                </div>
                                <div class="col-span-1 text-center"></div>
                                <div class="col-span-4">
                                    <x-input-label for="end_date">
                                        <span class="text-red-500 ml-1">*</span>End Date
                                    </x-input-label>
                                </div>
                            </div>
                            <div class="grid grid-cols-9 items-center gap-1">
                                <div class="col-span-4">
                                    <x-text-input id="start_date" name="start_date" type="date" class="w-full"
                                        value="{{ old('start_date', $itinerary->start_date?->format('Y-m-d')) }}" />
                                    @error('start_date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-span-1 text-center text-lg">〜</div>
                                <div class="col-span-4">
                                    <x-text-input id="end_date" name="end_date" type="date" class="w-full"
                                        value="{{ old('end_date', $itinerary->end_date?->format('Y-m-d')) }}" />
                                    @error('end_date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- グループ --}}
                        @if (Auth::id() === $itinerary->created_by)
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
                            </div>
                        @else
                            <div class="md:col-span-2">
                                <x-input-label for="group_id" value="Group" />
                                <div class="w-full py-2 px-3 border border-gray-300 rounded-md bg-gray-100">
                                    {{ $itinerary->group?->name ?? 'No Group' }}
                                </div>
                            </div>
                        @endif

                        {{-- 更新ボタン --}}
                        <div class="md:col-span-1 text-end">
                            <button type="submit"
                                class="w-full md:w-auto px-4 mt-5 py-2 bg-green-500 text-white rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400">
                                Update
                            </button>
                        </div>
                    </div>

                    {{-- Mainセクション（目的地入力 + Google Map） --}}
                    <div class="flex flex-col md:flex-row gap-4">
                        {{-- 目的地入力欄 --}}
                        <div class="flex-1 bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-inner relative flex flex-col">
                            {{-- Hiddenフィールド --}}
                            <input type="hidden" name="daily_distances" id="daily_distances" />
                            <input type="hidden" name="daily_durations" id="daily_durations" />
                            <input type="hidden" name="total_distance" id="total_distance" />
                            <input type="hidden" name="total_duration" id="total_duration" />
                            <input type="hidden" name="initial_place" id="initial_place" />

                            <div id="dateFieldsContainer" class="flex-1 overflow-y-auto max-h-[530px] space-y-4"></div>
                            <div id="totalSummary" class="mt-4 text-right text-sm text-gray-600 dark:text-gray-300 hidden"></div>
                        </div>

                        {{-- Google Map --}}
                        <div class="md:w-1/2 w-full bg-white dark:bg-gray-700 rounded-lg p-2 shadow relative">
                            <div id="map" class="h-72 md:h-[580px] w-full rounded-md border"></div>
                        </div>
                    </div>
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

                {{-- スクリプト --}}
                @push('scripts')
                    <script>
                        window.existingData = @json($itineraryData['destinations'] ?? []);
                        window.originalGroupId = "{{ $itinerary->group_id ?? '' }}";
                    </script>
                    <script src="{{ asset('js/itineraries/map.js') }}"></script>
                    <script src="{{ asset('js/itineraries/edit.js') }}"></script>
                    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initMap" async defer></script>
                @endpush

            </div>
        </div>
    </div>
    {{-- Scroll to Top Button --}}
    <button id="scrollToTopBtn"
        class="fixed bottom-12 left-1/2 transform -translate-x-1/2 z-50 bg-green-400 text-white rounded-full p-1 shadow-lg transition-opacity duration-300 opacity-0 pointer-events-none md:hidden"
        aria-label="Scroll to top">
        <i class="fa-solid fa-arrow-up"></i> Go to Top
    </button>


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
