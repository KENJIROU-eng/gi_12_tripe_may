<x-app-layout>
    <div class="py-4 min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4">
                <div class="text-black dark:text-gray-100">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center mb-10">
                        {{-- 戻るボタン --}}
                        <div class="absolute left-0 top-0 mt-1 ml-2 z-10">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm text-blue-500 hover:underline">
                                <i class="fa-solid fa-arrow-left mr-1"></i> Back
                            </a>
                        </div>
                        <h1 class="text-4xl md:text-6xl font-bold text-gray-800 dark:text-gray-100 animate-fadeIn">
                            <i class="fa-solid fa-i"></i>
                            <i class="fa-solid fa-t"></i>
                            <i class="fa-solid fa-i"></i>
                            <i class="fa-solid fa-n"></i>
                            <i class="fa-solid fa-e"></i>
                            <i class="fa-solid fa-r"></i>
                            <i class="fa-solid fa-a"></i>
                            <i class="fa-solid fa-r"></i>
                            <i class="fa-solid fa-y"></i>
                        </h1>
                        <a href="{{ route('itinerary.share') }}" class="absolute right-6 md:right-40 text-green-500">
                            <i class="fa-solid fa-circle-plus text-xl"></i>
                        </a>
                    </div>

                    {{-- Contents --}}
                    <div class="max-w-6xl mx-auto mt-8">
                        {{-- header --}}
                        <div class="hidden md:grid md:grid-cols-12 items-center text-sm font-semibold border-b-2 border-gray-500 pb-2">
                            <div class="md:col-span-1 ms-4">Avatar</div>
                            <div class="md:col-span-2 cursor-pointer flex items-center gap-1" data-sort="user">
                                Created by <span class="sort-icon" data-key="user"></span>
                            </div>
                            <div class="md:col-span-2 cursor-pointer flex items-center gap-1" data-sort="group">
                                Group <span class="sort-icon" data-key="group"></span>
                            </div>
                            <div class="md:col-span-3 cursor-pointer flex items-center gap-1" data-sort="date">
                                Date <span class="sort-icon" data-key="date"></span>
                            </div>
                            <div class="md:col-span-3 cursor-pointer flex items-center gap-1" data-sort="title">
                                Title <span class="sort-icon" data-key="title"></span>
                            </div>
                            <div class="md:col-span-1 text-center"></div>
                        </div>

                        {{-- filter row --}}
                        <div class="hidden md:grid md:grid-cols-12 items-center text-sm text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 py-2">
                            <div class="md:col-span-1"></div>
                            <div class="md:col-span-2 px-2">
                                <select id="filterUser" class="w-full border-gray-300 rounded">
                                    <option value="">All</option>
                                    @foreach ($all_itineraries->pluck('user')->unique('id') as $user)
                                        <option value="{{ strtolower($user->name) }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2 px-2">
                                <select id="filterGroup" class="w-full border-gray-300 rounded">
                                    <option value="">All</option>
                                    @foreach ($all_itineraries->pluck('group')->filter()->unique('id') as $group)
                                        <option value="{{ strtolower($group->name) }}">{{ $group->name }}</option>
                                    @endforeach
                                    <option value="no group">No Group</option>
                                </select>
                            </div>
                            <div class="md:col-span-3 px-2 flex gap-1">
                                <input type="date" id="filterDateFrom" class="w-1/2 border-gray-300 rounded text-sm">
                                <input type="date" id="filterDateTo" class="w-1/2 border-gray-300 rounded text-sm">
                            </div>
                            <div class="md:col-span-3 px-2">
                                <input id="searchInput" type="text" placeholder="Search title..." class="w-full border-gray-300 rounded px-2 py-1 text-sm">
                            </div>
                            <div class="md:col-span-1 flex justify-start items-center px-2">
                                <button id="clearSearchBtn" class="text-gray-400 hover:text-gray-600 text-sm border rounded px-2 py-1">
                                    <i class="fa-solid fa-xmark mr-1"></i> Clear
                                </button>
                            </div>
                        </div>

                        {{-- mobile filter UI --}}
                        <div class="md:hidden grid grid-cols-1 gap-3 p-4 bg-gray-100 dark:bg-gray-700 text-sm rounded mb-4">
                            <div>
                                <label for="mobileSort" class="block text-gray-700 dark:text-gray-300 mb-1">Sort by</label>
                                <select id="mobileSort" class="w-full rounded border-gray-300">
                                    <option value="">Default</option>
                                    <option value="user">Created by</option>
                                    <option value="group">Group</option>
                                    <option value="date">Date</option>
                                    <option value="title">Title</option>
                                </select>
                            </div>

                            <div>
                                <label for="mobileFilterUser" class="block text-gray-700 dark:text-gray-300 mb-1">Created by</label>
                                <select id="mobileFilterUser" class="w-full rounded border-gray-300">
                                    <option value="">All</option>
                                    @foreach ($all_itineraries->pluck('user')->unique('id') as $user)
                                        <option value="{{ strtolower($user->name) }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="mobileFilterGroup" class="block text-gray-700 dark:text-gray-300 mb-1">Group</label>
                                <select id="mobileFilterGroup" class="w-full rounded border-gray-300">
                                    <option value="">All</option>
                                    @foreach ($all_itineraries->pluck('group')->filter()->unique('id') as $group)
                                        <option value="{{ strtolower($group->name) }}">{{ $group->name }}</option>
                                    @endforeach
                                    <option value="no group">No Group</option>
                                </select>
                            </div>

                            <div class="flex gap-2">
                                <div class="w-1/2">
                                    <label for="mobileFilterDateFrom" class="block text-gray-700 dark:text-gray-300 mb-1">From</label>
                                    <input type="date" id="mobileFilterDateFrom" class="w-full rounded border-gray-300 text-sm">
                                </div>
                                <div class="w-1/2">
                                    <label for="mobileFilterDateTo" class="block text-gray-700 dark:text-gray-300 mb-1">To</label>
                                    <input type="date" id="mobileFilterDateTo" class="w-full rounded border-gray-300 text-sm">
                                </div>
                            </div>

                            <div>
                                <label for="mobileSearchInput" class="block text-gray-700 dark:text-gray-300 mb-1">Title</label>
                                <input type="text" id="mobileSearchInput" placeholder="Search title..." class="w-full rounded border-gray-300 px-2 py-1 text-sm">
                            </div>

                            <div class="flex justify-end">
                                <button id="mobileClearSearchBtn" class="text-gray-500 hover:text-gray-800 text-sm border rounded px-3 py-1">
                                    <i class="fa-solid fa-xmark mr-1"></i> Clear
                                </button>
                            </div>
                        </div>


                        {{-- body --}}
                        <div id="scrollContainer" class="max-h-[600px] overflow-y-auto border rounded mb-3">
                            <div id="itineraryContainer" class="max-w-6xl mx-auto">
                                @foreach ($all_itineraries as $itinerary)
                                    <div class="flex flex-col md:grid md:grid-cols-12 items-center text-md gap-4 py-2 text-center border-b itinerary-row"
                                        data-user="{{ strtolower($itinerary->user->name) }}"
                                        data-group="{{ strtolower($itinerary->group->name ?? 'no-group') }}"
                                        data-title="{{ strtolower($itinerary->title) }}"
                                        data-date="{{ $itinerary->start_date }}"
                                        data-created="{{ $itinerary->created_at }}">

                                        {{-- user avatar --}}
                                        <div class="md:col-span-1 w-full flex justify-center md:justify-start ms-6">
                                            <a href="{{ route('profile.show', $itinerary->created_by) }}">
                                                @if ($itinerary->user->avatar)
                                                    <img src="{{ $itinerary->user->avatar }}" alt="{{ $itinerary->user->name }}" class="w-12 h-12 rounded-full object-cover">
                                                @else
                                                    <i class="fa-solid fa-circle-user text-3xl text-gray-400"></i>
                                                @endif
                                            </a>
                                        </div>

                                        {{-- created by --}}
                                        <div class="md:col-span-2 w-full flex justify-center md:justify-start">
                                            <a href="{{ route('profile.show', $itinerary->created_by) }}" class="text-blue-600">{{ $itinerary->user->name }}</a>
                                        </div>

                                        {{-- group --}}
                                        <div class="md:col-span-2 w-full flex justify-center md:justify-start">
                                            @if ($itinerary->group)
                                                <a href="{{ route('message.show', $itinerary->group->id) }}" class="text-blue-600">{{ $itinerary->group->name ?? 'No Group' }}</a>
                                            @else
                                                <span class="text-gray-400">No Group</span>
                                            @endif
                                        </div>

                                        {{-- date --}}
                                        <div class="md:col-span-3 w-full text-center md:text-start">
                                            <p class="inline-block max-w-full md:w-60 text-sm md:text-base">
                                                {{ \Carbon\Carbon::parse($itinerary->start_date)->format('M. d, Y') }} ～
                                                {{ \Carbon\Carbon::parse($itinerary->end_date)->format('M. d, Y') }}
                                            </p>
                                        </div>

                                        {{-- title --}}
                                        <div class="md:col-span-3 w-full text-center md:text-start">
                                            <a href="{{ route('itinerary.show', $itinerary->id) }}">
                                                <p class="text-blue-600 hover:underline break-words">{{ Str::limit($itinerary->title, 30) }}</p>
                                            </a>
                                        </div>

                                        {{-- actions --}}
                                        <div class="md:col-span-1 w-full flex justify-center space-x-4">
                                            <a href="{{ route('itinerary.edit', $itinerary->id) }}" title="Edit">
                                                <i class="fa-solid fa-pen text-yellow-300"></i>
                                            </a>
                                            @include('itineraries.modals.delete', ['itinerary' => $itinerary])
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/itineraries/index.js') }}"></script>
    @endpush

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
