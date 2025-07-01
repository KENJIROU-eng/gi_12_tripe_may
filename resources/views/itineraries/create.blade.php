<x-app-layout class="h-screen flex flex-col overflow-hidden">
    <div class="min-h-screen bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('https://res.cloudinary.com/dpwrycc89/image/upload/v1750757614/pexels-jplenio-1133505_ijwxpn.jpg');">
        <div class="pt-8 flex-1 overflow-y-auto flex flex-col lg:flex-row gap-4 max-w-screen-3xl mx-auto px-4 pb-32"">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4 mx-auto w-full max-w-6xl">
                {{-- タイトル --}}
                <div class="relative flex flex-col md:flex-row items-center justify-center text-center mb-10 gap-2 md:gap-0">
                {{-- 戻るボタン（左） --}}
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2">
                    <a href="{{ route('itinerary.index') }}" class="inline-flex items-center text-sm text-blue-500 hover:underline">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Back
                    </a>
                </div>

                {{-- タイトル（中央） --}}
                <h1 class="text-3xl sm:text-4xl md:text-6xl font-bold text-gray-800 dark:text-gray-100 animate-fadeIn">
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
                            <x-input-label for="title">
                                <span class="text-red-500 ml-1">*</span>Title
                            </x-input-label>
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
                                    <x-text-input name="start_date" type="date" id="start_date" class="w-full" />
                                    @error('start_date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-span-1 text-center text-lg">〜</div>
                                <div class="col-span-4">
                                    <x-text-input name="end_date" type="date" id="end_date" class="w-full" />
                                    @error('end_date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Createボタン --}}
                        <div class="md:col-span-1 flex items-start justify-end">
                            <button type="submit" class="w-full md:w-auto mt-5 px-5 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow transition-all duration-200 ease-in-out">
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
                            <div id="dateFieldsContainer" class="flex-1 overflow-y-auto max-h-[500px] space-y-4">
                                {{-- 動的にJavaScriptで追加される --}}
                            </div>
                            {{-- 合計表示 --}}
                            <div id="totalSummary" class="mt-4 text-right text-sm text-gray-600 dark:text-gray-300 hidden"></div>
                        </div>
                        {{-- 右カラム（地図） --}}
                        <div class="md:w-1/2 w-full bg-white dark:bg-gray-700 rounded-lg p-2 shadow relative">
                            <div id="map" class="h-64 md:h-[550px] w-full rounded-md border"></div>
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
    {{-- Scroll to Top Button --}}
    <button id="scrollToTopBtn" class="fixed bottom-12 left-1/2 transform -translate-x-1/2 z-50 bg-green-500 text-white rounded-full p-1 shadow-lg transition-opacity duration-300 opacity-0 pointer-events-none md:hidden" aria-label="Scroll to top">
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

