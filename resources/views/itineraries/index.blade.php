<x-app-layout>
    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="text-black dark:text-gray-100">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-4xl md:text-6xl font-bold absolute left-1/2 transform -translate-x-1/2">Itinerary</h1>
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
                        {{-- user --}}
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
                            {{-- group --}}
                            <div class="md:col-span-2 px-2">
                                <select id="filterGroup" class="w-full border-gray-300 rounded">
                                    <option value="">All</option>
                                    @foreach ($all_itineraries->pluck('group')->filter()->unique('id') as $group)
                                        <option value="{{ strtolower($group->name) }}">{{ $group->name }}</option>
                                    @endforeach
                                    <option value="no group">No Group</option>
                                </select>
                            </div>
                            {{-- date --}}
                            <div class="md:col-span-3 px-2 flex gap-1">
                                <input type="date" id="filterDateFrom" class="w-1/2 border-gray-300 rounded text-sm">
                                <input type="date" id="filterDateTo" class="w-1/2 border-gray-300 rounded text-sm">
                            </div>
                            {{-- title --}}
                            <div class="md:col-span-3 px-2">
                                <input id="searchInput" type="text" placeholder="Search title..." class="w-full border-gray-300 rounded px-2 py-1 text-sm">
                            </div>
                            {{-- clear button --}}
                            <div class="md:col-span-1 flex justify-start items-center px-2">
                                <button id="clearSearchBtn" class="text-gray-400 hover:text-gray-600 text-sm border rounded px-2 py-1">
                                    <i class="fa-solid fa-xmark mr-1"></i> Clear
                                </button>
                            </div>
                        </div>

                        {{-- body --}}
                        <div id="scrollContainer" class="max-h-[660px] overflow-y-auto border rounded mb-3">
                            <div id="itineraryContainer" class="max-w-6xl mx-auto">
                                @forelse ($all_itineraries as $itinerary)
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
                                            <a href="{{ route('profile.show', $itinerary->created_by) }}">{{ $itinerary->user->name }}</a>
                                        </div>
                                        {{-- group --}}
                                        <div class="md:col-span-2 w-full flex justify-center md:justify-start">
                                            @if ($itinerary->group)
                                                <a href="{{ route('message.show', $itinerary->group->id) }}">{{ $itinerary->group->name ?? 'No Group' }}</a>
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
                                                <p class="text-blue-600 hover:underline break-words">{{ Str::limit($itinerary->title, 50) }}</p>
                                            </a>
                                        </div>
                                        {{-- delete --}}
                                        <div class="md:col-span-1 w-full flex justify-center space-x-4">
                                            <a href="{{ route('itinerary.edit', $itinerary->id) }}" title="Edit">
                                                <i class="fa-solid fa-pen text-yellow-300"></i>
                                            </a>
                                            @include('itineraries.modals.delete', ['itinerary' => $itinerary])
                                        </div>

                                    </div>
                                @empty
                                    <div class="text-center text-lg my-60">
                                        <h2 class="mb-4 text-gray-500">No itinerary created yet.</h2>
                                        <div class="text-green-500">
                                            <a href="{{ route('itinerary.share') }}">
                                                <i class="fa-solid fa-plus"></i>
                                                add itinerary
                                            </a>
                                        </div>
                                    </div>
                                @endforelse
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
</x-app-layout>
