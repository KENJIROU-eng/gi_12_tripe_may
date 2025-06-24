<x-app-layout>
    <div class="py-4 min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4">
                {{-- タイトル --}}
                <div class="relative flex items-center justify-center mb-4">
                    {{-- 戻るボタン --}}
                        <div class="absolute left-0 top-0 mt-1 ml-2 z-10">
                            <a href="{{ route('itinerary.index') }}" class="inline-flex items-center text-sm text-blue-500 hover:underline">
                                <i class="fa-solid fa-arrow-left mr-1"></i> Back
                            </a>
                        </div>
                    <h1 class="text-4xl md:text-6xl font-bold text-gray-800 dark:text-gray-100 animate-fadeIn">
                        <i class="fa-solid fa-c"></i>
                        <i class="fa-solid fa-r"></i>
                        <i class="fa-solid fa-e"></i>
                        <i class="fa-solid fa-a"></i>
                        <i class="fa-solid fa-t"></i>
                        <i class="fa-solid fa-e"></i>
                    </h1>
                </div>
                <form id="itineraryForm" action="{{ route('itinerary.store') }}" method="POST" class="space-y-8">
                    @csrf
                    {{-- ヘッダー入力 --}}
                    <div class="grid grid-cols-1 md:grid-cols-10 gap-6">
                        {{-- タイトル --}}
                        <div class="md:col-span-4">
                            <x-input-label for="title" value="Title" />
                            <x-text-input name="title" id="title" placeholder="Please enter a title" required maxlength="100" class="w-full" />
                            <div id="titleCharCount" class="right-2 top-2 text-sm text-gray-400">
                                0 / 100
                            </div>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- 日付 --}}
                        <div class="md:col-span-5">
                            <div class="grid grid-cols-9 gap-2">
                                <div class="col-span-4">
                                    <x-input-label for="start_date" value="Start Date" />
                                    <x-text-input name="start_date" type="date" id="start_date" class="w-full" />
                                    @error('start_date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-span-1 flex items-center justify-center text-lg text-gray-500 dark:text-gray-300">~</div>
                                <div class="col-span-4">
                                    <x-input-label for="end_date" value="End Date" />
                                    <x-text-input name="end_date" type="date" id="end_date" class="w-full" />
                                    @error('end_date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{-- Createボタン --}}
                        <div class="md:col-span-1 flex items-end justify-end">
                            <button type="submit" class="w-full md:w-auto px-5 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow transition-all duration-200 ease-in-out">
                                Create
                            </button>
                        </div>
                    </div>
                    {{-- メインエリア（左：目的地入力欄, 右：マップ） --}}
                    <div class="flex flex-col md:flex-row gap-4">
                        {{-- 左カラム --}}
                        <div class="flex-1 bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-inner relative flex flex-col">
                            {{-- hidden inputs --}}
                            <input type="hidden" name="daily_distances" id="daily_distances" />
                            <input type="hidden" name="daily_durations" id="daily_durations" />
                            <input type="hidden" name="total_distance" id="total_distance" />
                            <input type="hidden" name="total_duration" id="total_duration" />
                            <input type="hidden" name="initial_place" id="initial_place" />
                            {{-- 日付別フォーム --}}
                            <div id="dateFieldsContainer" class="flex-1 overflow-y-auto max-h-[560px] space-y-4">
                                {{-- 動的にJavaScriptで追加される --}}
                            </div>
                            {{-- 合計表示 --}}
                            <div id="totalSummary" class="mt-4 text-right text-sm text-gray-600 dark:text-gray-300 hidden"></div>
                        </div>
                        {{-- 右カラム（地図） --}}
                        <div class="md:w-1/2 w-full bg-white dark:bg-gray-700 rounded-lg p-2 shadow relative">
                            <div id="map" class="h-64 md:h-[600px] w-full rounded-md border"></div>
                        </div>
                    </div>
                </form>
                {{-- スクリプト --}}
                @push('scripts')
                    <script src="{{ asset('js/itineraries/map.js') }}"></script>
                    <script src="{{ asset('js/itineraries/create.js') }}"></script>
                    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initMap" async defer></script>
                @endpush
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

