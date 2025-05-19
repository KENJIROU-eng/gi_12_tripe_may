<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100 h-full">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Bill</h1>
                        <a href="{{ route('goDutch.create') }}" class="absolute right-40">
                            <i class="fa-solid fa-circle-plus text-lg"></i>
                        </a>
                    </div>
                    {{-- contents --}}
                    <div class="mx-auto h-4/5 mt-8 overflow-hidden">
                        {{-- @forelse ($all_itineraries as $itinerary)
                            <div class="grid grid-cols-12 items-center text-center text-md my-4 gap-4 pb-4">
                                {{-- user avatar --}}
                                {{-- <div class="col-span-2"> --}}
                                    {{-- user show --}}
                                    {{-- <a href="#">
                                        @if ($itinerary->user->avatar)
                                            <img src="{{ $itinerary->user->avatar }}" alt="{{ $itinerary->user->name }}">
                                        @else
                                            <i class="fa-solid fa-circle-user"></i>
                                        @endif
                                    </a>
                                </div> --}}
                                {{-- date --}}
                                {{-- <div class="col-span-3 text-start">
                                    <p class="inline-block w-60">{{ \Carbon\Carbon::parse($itinerary->start_date)->format('M,d,Y') }} ～ {{ \Carbon\Carbon::parse($itinerary->end_date)->format('M,d,Y') }}</p>
                                </div> --}}

                                {{-- title --}}
                                {{-- <div class="col-span-5 text-start"> --}}
                                    {{-- itinerary show --}}
                                    {{-- <a href="{{ route('itinerary.show', $itinerary->id) }}">
                                        <p>{{ Str::limit($itinerary->title, 50) }}</p>
                                    </a>
                                </div> --}}

                                {{-- delete button(modal) --}}
                                {{-- @include('itineraries.modals.delete', ['itinerary' => $itinerary]) --}}
                            {{-- </div> --}}
                        {{-- @empty --}}
                            <div class="text-center text-lg my-60">
                                <h2 class="mb-4 text-gray-500">No Bill created yet.</h2>
                                <div class="text-blue-500">
                                    <a href="{{ route('goDutch.create') }}">
                                        <i class="fa-solid fa-plus"></i>
                                        add Bill
                                    </a>
                                </div>
                            </div>
                        {{-- @endforelse --}}
                    </div>

                    {{-- paginate --}}
                    {{-- <div class="flex justify-center">
                        {{ $all_itineraries->links('vendor.pagination.custom') }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
