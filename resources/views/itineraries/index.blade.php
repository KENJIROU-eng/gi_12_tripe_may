<x-app-layout class="h-screen flex flex-col overflow-hidden">
    <div class="min-h-screen bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('https://res.cloudinary.com/dpwrycc89/image/upload/v1750757614/pexels-jplenio-1133505_ijwxpn.jpg');">
        <div class="pt-8 flex-1 overflow-y-auto flex flex-col lg:flex-row gap-4 max-w-screen-3xl mx-auto px-4 pb-32">
            {{-- 左 --}}
            <div class="w-full lg:w-1/5"></div>

            {{-- 中央 --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4 mx-auto">
                <div class="text-black dark:text-gray-100">
                    {{-- title --}}
                    <div class="flex flex-col md:flex-row items-center justify-between text-center mb-10 gap-2 md:gap-0 relative">
                        {{-- Backボタン（モバイルは上、PCは左） --}}
                        <div class="order-1 md:order-1">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm text-blue-500 hover:underline">
                                <i class="fa-solid fa-arrow-left mr-1"></i> Back
                            </a>
                        </div>

                        {{-- タイトル（モバイル中央、PC中央） --}}
                        <h1 class="order-2 md:order-2 text-3xl sm:text-4xl md:text-6xl font-bold text-gray-800 dark:text-gray-100 animate-fadeIn">
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

                        {{-- Createボタン（モバイルは下、PCは右） --}}
                        <div class="order-3 md:order-3">
                            <a href="{{ route('itinerary.share') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded-lg shadow">
                                <i class="fa-solid fa-circle-plus mr-2"></i>
                                Create
                            </a>
                        </div>
                    </div>

                    {{-- Contents --}}
                    <div class="max-w-6xl mx-auto mt-8">
                        {{-- header --}}
                        <div class="hidden md:grid md:grid-cols-12 items-center text-sm font-semibold border-b-2 border-gray-500 pb-2">
                            <div class="md:col-span-1 ms-4">Avatar</div>
                                <div class="md:col-span-2 cursor-pointer flex items-center gap-1 hover:bg-gray-200 dark:hover:bg-gray-700 px-2 py-1 rounded" data-sort="user">
                                    Created by <i class="fa-solid fa-sort sort-icon" data-key="user"></i>
                                </div>
                                <div class="md:col-span-2 cursor-pointer flex items-center gap-1 hover:bg-gray-200 dark:hover:bg-gray-700 px-2 py-1 rounded" data-sort="group">
                                    Group <i class="fa-solid fa-sort sort-icon" data-key="group"></i>
                                </div>
                                <div class="md:col-span-3 cursor-pointer flex items-center gap-1 hover:bg-gray-200 dark:hover:bg-gray-700 px-2 py-1 rounded" data-sort="date">
                                    Date <i class="fa-solid fa-sort sort-icon" data-key="date"></i>
                                </div>
                                <div class="md:col-span-3 cursor-pointer flex items-center gap-1 hover:bg-gray-200 dark:hover:bg-gray-700 px-2 py-1 rounded" data-sort="title">
                                    Title <i class="fa-solid fa-sort sort-icon" data-key="title"></i>
                                </div>
                                <div class="md:col-span-1 cursor-pointer flex items-center gap-1 hover:bg-gray-200 dark:hover:bg-gray-700 px-2 py-1 rounded" data-sort="finished">
                                    Status <i class="fa-solid fa-sort sort-icon" data-key="finished"></i>
                                </div>
                        </div>

                        {{-- filter row --}}
                        <div class="hidden md:grid md:grid-cols-12 items-center text-sm text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 py-2">
                            <div class="md:col-span-1"></div>
                            <div class="md:col-span-2 px-2">
                                <select id="filterUser" class="w-full border-gray-300 rounded max-h-40 overflow-y-auto"">
                                    <option value="">All</option>
                                    @foreach ($all_itineraries->pluck('user')->unique('id') as $user)
                                        <option value="{{ strtolower($user->name) }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2 px-2">
                                <select id="filterGroup" class="w-full border-gray-300 rounded max-h-40 overflow-y-auto">
                                    <option value="">All</option>
                                    @foreach ($all_itineraries->pluck('group')->filter()->unique('id') as $group)
                                        <option value="{{ strtolower($group->name) }}">{{ $group->name }}</option>
                                    @endforeach
                                    <option value="no-group">No Group</option>
                                </select>
                            </div>
                            <div class="md:col-span-3 px-2 flex gap-1">
                                <input type="date" id="filterDateFrom" class="w-1/2 border-gray-300 rounded text-sm">
                                <input type="date" id="filterDateTo" class="w-1/2 border-gray-300 rounded text-sm">
                            </div>
                            <div class="md:col-span-3 px-2">
                                <input id="searchInput" type="text" placeholder="Search by title..." class="w-full border-gray-300 rounded px-2 py-1 text-sm">
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
                                    <option value="finished">Done</option>
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
                        <div id="scrollContainer" class="w-full overflow-x-hidden overflow-y-auto border rounded mb-2 max-h-none md:max-h-[580px]">

                            <div id="itineraryContainer" class="max-w-6xl mx-auto">
                                @forelse ($all_itineraries as $itinerary)
                                    <div class="itinerary-row w-full flex flex-col md:grid md:grid-cols-12 gap-2 py-2 border-b text-sm md:text-base {{ $itinerary->finish_at ? 'opacity-50' : '' }}"
                                        data-user="{{ strtolower($itinerary->user->name) }}"
                                        data-group="{{ strtolower($itinerary->group->name ?? 'no-group') }}"
                                        data-date="{{ $itinerary->start_date }}"
                                        data-title="{{ strtolower($itinerary->title) }}"
                                        data-created="{{ $itinerary->created_at }}"
                                        data-finished="{{ $itinerary->finish_at ? '1' : '0' }}">

                                        {{-- user avatar --}}
                                        <div class="md:col-span-1 flex flex-col items-center md:items-start justify-start ms-0 md:ms-6">
                                            <a href="{{ route('profile.show', $itinerary->created_by) }}">
                                                @if ($itinerary->user->avatar)
                                                    <img src="{{ $itinerary->user->avatar }}" alt="{{ $itinerary->user->name }}"
                                                        class="w-12 h-12 rounded-full object-cover" />
                                                @else
                                                    <i class="fa-solid fa-circle-user text-gray-400 w-12 h-12 text-[48px] leading-[48px] rounded-full"></i>
                                                @endif
                                            </a>
                                        </div>

                                        {{-- created by --}}
                                        <div class="md:col-span-2 flex flex-col items-center md:items-center justify-center text-center md:text-center">
                                            <a href="{{ route('profile.show', $itinerary->created_by) }}" class="text-blue-600 font-semibold">
                                                {{ Str::limit($itinerary->user->name, 20) }}
                                            </a>
                                        </div>

                                        {{-- group --}}
                                        <div class="md:col-span-2 flex flex-col items-center md:items-center justify-center text-center md:text-center">
                                            @if ($itinerary->group)
                                                <a href="{{ route('message.show', $itinerary->group->id) }}" class="text-blue-600 font-semibold">{{ Str::limit($itinerary->group->name, 15) }}</a>
                                            @else
                                                <span class="text-gray-400">No Group</span>
                                            @endif
                                        </div>

                                        {{-- date --}}
                                        <div class="md:col-span-3 flex flex-col items-center md:items-center justify-center text-center md:text-center">
                                            <span>
                                                {{ \Carbon\Carbon::parse($itinerary->start_date)->format('M. d, Y') }}
                                                ～ {{ \Carbon\Carbon::parse($itinerary->end_date)->format('M. d, Y') }}
                                            </span>
                                        </div>

                                        {{-- title --}}
                                        <div class="md:col-span-3 flex flex-col items-center md:items-center justify-center text-center md:text-center">
                                            <a href="{{ route('itinerary.show', $itinerary->id) }}" class="text-blue-600 hover:underline font-semibold">
                                                {{ Str::limit($itinerary->title, 30) }}
                                            </a>
                                        </div>

                                        {{-- actions --}}
                                        <div class="md:col-span-1 flex justify-center items-center space-x-4">
                                            @if (!$itinerary->finish_at)
                                                {{-- 編集ボタン --}}
                                                <a href="{{ route('itinerary.edit', $itinerary->id) }}" title="Edit">
                                                    <i class="fa-solid fa-pen text-yellow-300 text-lg hover:text-yellow-700"></i>
                                                </a>

                                                {{-- 削除ボタン（作成者のみ） --}}
                                                @if (Auth::id() === $itinerary->created_by)
                                                    <span class="text-red-500">
                                                        @include('itineraries.modals.delete', ['itinerary' => $itinerary, 'showText' => false])
                                                    </span>
                                                @else
                                                    {{-- 非表示でもスペース確保 --}}
                                                    <span class="w-[18px] h-[18px] inline-block"></span>
                                                @endif
                                            @else
                                                {{-- 完了ラベル --}}
                                                <span class="text-xs bg-green-500 text-white px-2 py-1 rounded">
                                                    <i class="fa-solid fa-check mr-1"></i> Finished
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                @empty
                                    <p class="text-center text-gray-500 py-4">No itineraries found.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 右 --}}
            <div class="w-full lg:w-1/5">
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 space-y-4 text-sm text-gray-800 dark:text-gray-100">
                    <h2 class="text-base font-semibold mb-2 text-gray-900 dark:text-gray-100">
                        How to Use
                    </h2>

                    {{-- Step 1: Filter --}}
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-filter text-blue-500 mt-1"></i>
                        <div>
                            <p class="font-semibold">Step 1: Filter the Itineraries</p>
                            <p>Use the filter options to narrow down the list by user, group, or date range.</p>
                        </div>
                    </div>

                    {{-- Step 2: Search --}}
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-magnifying-glass text-green-500 mt-1"></i>
                        <div>
                            <p class="font-semibold">Step 2: Search by Title</p>
                            <p>Type in a keyword to search itineraries by their title.</p>
                        </div>
                    </div>

                    {{-- Step 3: Sort --}}
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-sort text-yellow-500 mt-1"></i>
                        <div>
                            <p class="font-semibold">Step 3: Sort the List</p>
                            <p>Click column headers (Title, Group, Date, etc.) to sort itineraries accordingly.</p>
                        </div>
                    </div>

                    {{-- Step 4: View or Edit --}}
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-eye text-indigo-500 mt-1"></i>
                        <div>
                            <p class="font-semibold">Step 4: View or Edit</p>
                            <p>Click on a title to view its details. If it's still in progress, you may edit or delete it.</p>
                        </div>
                    </div>

                    {{-- Step 5: Create New --}}
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-plus text-teal-500 mt-1"></i>
                        <div>
                            <p class="font-semibold">Step 5: Create a New Itinerary</p>
                            <p>Click the “Create” button at the top to start a new itinerary.</p>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="border-t pt-4 text-xs text-gray-600 dark:text-gray-300">
                        <p><i class="fa-solid fa-triangle-exclamation text-red-400 mr-1"></i> <strong>Note:</strong> Only the creator of an itinerary can delete it.</p>
                        <p><i class="fa-solid fa-lock text-yellow-400 mr-1"></i> Itineraries marked as <strong>Finished</strong> cannot be edited or deleted.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll to Top Button --}}
    <button id="scrollToTopBtn" class="fixed bottom-12 left-1/2 transform -translate-x-1/2 z-50 bg-green-500 text-white rounded-full p-1 shadow-lg transition-opacity duration-300 opacity-0 pointer-events-none md:hidden" aria-label="Scroll to top">
        <i class="fa-solid fa-arrow-up"></i> Go to Top
    </button>

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

        .sort-icon {
            transition: color 0.2s, transform 0.2s;
        }

        [data-sort]:hover .sort-icon {
            color: #3b82f6;
            transform: scale(1.1);
        }

        [data-sort] {
            transition: background-color 0.2s;
            border-radius: 0.375rem;
        }

        [data-sort]:hover {
            background-color: #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            [data-sort]:hover {
                background-color: #374151;
            }
        }
    </style>

</x-app-layout>
