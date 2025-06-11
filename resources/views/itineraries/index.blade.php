<x-app-layout>
    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="text-black dark:text-gray-100">
                    {{-- タイトル --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-3xl md:text-6xl font-bold absolute left-1/2 transform -translate-x-1/2">Itinerary</h1>
                        <a href="{{ route('itinerary.share') }}" class="absolute right-6 md:right-40 text-blue-500">
                            <i class="fa-solid fa-circle-plus text-xl"></i>
                        </a>
                    </div>

                    {{-- Contents --}}
                    <div class="max-w-6xl mx-auto h-full mt-8">
                        @forelse ($all_itineraries as $itinerary)
                            <div class="flex flex-col md:grid md:grid-cols-12 items-center text-md gap-4 py-2 text-center border-b">

                                {{-- user avatar --}}
                                <div class="md:col-span-2 w-full flex justify-center md:justify-start">
                                    <a href="#">
                                        @if ($itinerary->user->avatar)
                                            <img src="{{ $itinerary->user->avatar }}" alt="{{ $itinerary->user->name }}" class="w-12 h-12 rounded-full object-cover">
                                        @else
                                            <i class="fa-solid fa-circle-user text-3xl text-gray-400"></i>
                                        @endif
                                    </a>
                                </div>

                                {{-- date --}}
                                <div class="md:col-span-3 w-full text-center md:text-start">
                                    <p class="inline-block max-w-full md:w-60 text-sm md:text-base">
                                        {{ \Carbon\Carbon::parse($itinerary->start_date)->format('M. d, Y') }} ～
                                        {{ \Carbon\Carbon::parse($itinerary->end_date)->format('M. d, Y') }}
                                    </p>
                                </div>

                                {{-- title --}}
                                <div class="md:col-span-5 w-full text-center md:text-start">
                                    <a href="{{ route('itinerary.show', $itinerary->id) }}">
                                        <p class="text-blue-600 hover:underline break-words">{{ Str::limit($itinerary->title, 50) }}</p>
                                    </a>
                                </div>

                                {{-- delete --}}
                                <div class="md:col-span-2 w-full text-center">
                                    @include('itineraries.modals.delete', ['itinerary' => $itinerary])
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-lg my-60">
                                <h2 class="mb-4 text-gray-500">No itinerary created yet.</h2>
                                <div class="text-blue-500">
                                    <a href="{{ route('itinerary.share') }}">
                                        <i class="fa-solid fa-plus"></i>
                                        add itinerary
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Paginate --}}
                    <div class="flex justify-center my-8">
                        {{ $all_itineraries->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
