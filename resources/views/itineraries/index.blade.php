<x-app-layout>
    <div class="py-12 h-[880px]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-6xl font-bold absolute left-1/2 transform -translate-x-1/2">Itinerary</h1>
                        <a href="{{ route('itinerary.share') }}" class="absolute right-40">
                            <i class="fa-solid fa-circle-plus text-lg"></i>
                        </a>
                    </div>

                    {{-- contents --}}
                    <div class="max-w-6xl mx-auto h-full mt-8">
                        @forelse ($all_itineraries as $itinerary)
                            <div class="grid grid-cols-12 items-center text-center text-md my-4 gap-4 pb-4">
                                {{-- user avatar --}}
                                <div class="col-span-2">
                                    {{-- user show --}}
                                    <a href="#">
                                        @if ($itinerary->user->avatar)
                                            <img src="{{ $itinerary->user->avatar }}" alt="{{ $itinerary->user->name }}">
                                        @else
                                            <i class="fa-solid fa-circle-user"></i>
                                        @endif
                                    </a>
                                </div>
                                {{-- date --}}
                                <div class="col-span-3 text-start">
                                    <p class="inline-block w-60">{{ \Carbon\Carbon::parse($itinerary->start_date)->format('M,d,Y') }} ～ {{ \Carbon\Carbon::parse($itinerary->end_date)->format('M,d,Y') }}</p>
                                </div>

                                {{-- title --}}
                                <div class="col-span-5 text-start">
                                    {{-- itinerary show --}}
                                    <a href="{{ route('itinerary.show', $itinerary->id) }}">
                                        <p>{{ Str::limit($itinerary->title, 50) }}</p>
                                    </a>
                                </div>

                                {{-- delete button(modal) --}}
                                @include('itineraries.modals.delete', ['itinerary' => $itinerary])
                            </div>
                        @empty
                            <div class="text-center text-lg my-60">
                                <h2 class="mb-4 text-gray-500">No itinerary created yet.</h2>
                                <div class="text-blue-500">
                                    <a href="#">
                                        <i class="fa-solid fa-plus"></i>
                                        add itinerary
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- paginate --}}
                    <div class="flex justify-center">
                        {{ $all_itineraries->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
