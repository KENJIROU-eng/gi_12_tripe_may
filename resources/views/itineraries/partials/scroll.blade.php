@forelse ($all_itineraries as $itinerary)
                                    <div class="itinerary-row w-full flex flex-col md:grid md:grid-cols-12 gap-2 py-4 border-b text-sm md:text-base"
                                        data-user="{{ strtolower($itinerary->user->name) }}"
                                        data-group="{{ strtolower($itinerary->group->name ?? 'no-group') }}"
                                        data-title="{{ strtolower($itinerary->title) }}"
                                        data-date="{{ $itinerary->start_date }}"
                                        data-created="{{ $itinerary->created_at }}">

                                        {{-- user avatar --}}
                                        <div class="md:col-span-1 flex flex-col items-center md:items-start justify-start ms-0 md:ms-6">
                                            <a href="{{ route('profile.show', $itinerary->created_by) }}">
                                                @if ($itinerary->user->avatar)
                                                    <img src="{{ $itinerary->user->avatar }}" alt="{{ $itinerary->user->name }}" class="w-12 h-12 rounded-full object-cover">
                                                @else
                                                    <i class="fa-solid fa-circle-user text-3xl text-gray-400"></i>
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
                                            <a href="{{ route('itinerary.edit', $itinerary->id) }}" title="Edit">
                                                <i class="fa-solid fa-pen text-yellow-300 text-lg"></i>
                                            </a>
                                            <span class="text-red-500">
                                                @include('itineraries.modals.delete', ['itinerary' => $itinerary])
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-500 py-4">No itineraries found.</p>
                                @endforelse
