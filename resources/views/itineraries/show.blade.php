<x-app-layout>
    <div class="py-12 h-[880px]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white shadow-md dark:bg-gray-800 overflow-hidden sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-6xl font-bold absolute left-1/2 transform -translate-x-1/2">Details</h1>
                    </div>

                    {{-- contents --}}
                    <div class="max-w-5xl mx-auto h-full mt-8">
                        <div class="bg-white shadow-md rounded-lg p-6 max-w-sm">
                            {{-- header --}}
                            <div class="grid grid-cols-5 items-center border-b">
                                <div class="col-span-4">
                                    <p class="text-gray-500">Title</p>
                                    <p class="font-bold">{{ $itinerary->title }}</p>
                                    <p class="text-gray-500">Date</p>
                                    <p class="font-bold">{{ \Carbon\Carbon::parse($itinerary->start_date)->format('M,d,Y') }} ～ {{ \Carbon\Carbon::parse($itinerary->end_date)->format('M,d,Y') }}</p>
                                </div>
                                <div class="col-span-1 flex items-center ms-4 gap-4">
                                    {{-- edit button --}}
                                    <a href="#">
                                        <i class="fa-solid fa-pen text-yellow-300"></i>
                                    </a>
                                    {{-- delete button(modal) --}}
                                    @include('itineraries.modals.delete', ['itinerary' => $itinerary])
                                </div>
                            </div>
                            {{-- body --}}
                            <div>
                                @foreach ($period as $date)
                                    <p class="my-4">{{ $date->format('M,d,Y') }}</p>

                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
