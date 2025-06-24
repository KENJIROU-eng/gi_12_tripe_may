<x-app-layout>
    <div class="mt-5 min-h-screen">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-black dark:text-gray-100">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold text-center">
                            Welcome to Tripe@s
                        </h1>
                    </div>

                    {{-- notification --}}
                    <div id="notify-box" class="fixed bottom-4 right-4 bg-white p-4 rounded shadow max-w-sm z-40">
                        <p>Do you wanna receive the notifications?</p>
                        <div class="flex gap-2 mt-4">
                            <button onclick="enableNotification()" class="bg-blue-500 text-white flex-1 py-1 rounded">Yes</button>
                            <button onclick="dismissNotification()" class="bg-gray-300 text-gray-800 flex-1 py-1 rounded">Cancel</button>
                        </div>
                    </div>

                    {{-- modal --}}
                    <div id="audio-permission-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50 hidden">
                        <div class="bg-white rounded-lg p-6 max-w-sm text-center shadow-lg">
                            <p class="text-gray-800 text-lg mb-6">
                                If you permit notification sounds, please click the button below
                            </p>
                            <div class="flex justify-center space-x-4">
                                <button id="enable-sound-btn" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">OK</button>
                                <button id="cancel-sound-btn" class="px-5 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                            </div>
                        </div>
                    </div>

                    {{-- contents --}}
                    <div class="mt-8">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                            {{-- calendar --}}
                            <div class="lg:col-span-2">
                                <div class="mb-4 text-center">
                                    <a href="{{ route('itinerary.share') }}" class="bg-green-500 py-2 px-4 text-lg rounded-md text-white inline-block w-full max-w-md hover:bg-green-600">
                                        <i class="fa-solid fa-plane-departure"></i> Create Itinerary
                                    </a>
                                </div>
                                <div class="bg-white shadow-lg rounded-lg min-h-[500px] flex flex-col justify-between">
                                    <div class="px-6 py-4 flex justify-between items-center">
                                        <button id="prev-month" class="text-gray-500 hover:text-gray-700">&lt; Previous</button>
                                        <h2 id="month-year" class="text-xl font-semibold"></h2>
                                        <button id="next-month" class="text-gray-500 hover:text-gray-700">Next &gt;</button>
                                    </div>
                                    <div class="grid grid-cols-7 border-b border-gray-200 text-sm">
                                        <template x-for="day in ['Sun.','Mon.','Tue.','Wed.','Thu.','Fri.','Sat.']">
                                            <div><p class="text-center py-2 font-semibold text-gray-600" x-text="day"></p></div>
                                        </template>
                                    </div>
                                    <div id="calendar-body" class="grid grid-cols-7 min-h-[300px] sm:min-h-[400px] lg:min-h-[500px]"></div>
                                </div>
                            </div>

                            {{-- ranking --}}
                            <div class="">
                                <div class="rounded-lg border-2 p-4 h-full max-h-[700px] overflow-y-auto">
                                    <h2 class="text-center text-lg font-semibold mb-6">Post Ranking</h2>
                                    <div class="space-y-6 min-h-[500px]">
                                        @foreach ($posts as $index => $post)
                                            <div class="space-y-2">
                                                <div class="text-center">
                                                    @if ($likeCounts[$index] == $likeCounts[0])
                                                        <div class="text-yellow-300">
                                                            <i class="fa-solid fa-crown"></i> 1st
                                                            <a href="{{ route('post.show', $post->id) }}" class="text-black hover:text-green-500">{{ $post->title }}</a>
                                                        </div>
                                                    @elseif ($likeCounts[$index] == $likeCounts[1])
                                                        <div class="text-gray-400">
                                                            <i class="fa-solid fa-crown"></i> 2nd
                                                            <a href="{{ route('post.show', $post->id) }}" class="text-black hover:text-green-500">{{ $post->title }}</a>
                                                        </div>
                                                    @else
                                                        <div class="text-yellow-500">
                                                            <i class="fa-solid fa-crown"></i> 3rd
                                                            <a href="{{ route('post.show', $post->id) }}" class="text-black hover:text-green-500">{{ $post->title }}</a>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex justify-center items-center gap-4">
                                                    <a href="{{ route('post.show', $post->id) }}">
                                                        <img src="{{ $post->image }}" alt="{{ $post->id }}" class="w-24 h-24 object-cover rounded hover:scale-105 transition-transform duration-300">
                                                    </a>
                                                    <div class="flex items-center gap-1">
                                                        <i class="fa-solid fa-heart text-red-500"></i>
                                                        <span>{{ $post->likes()->count() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-4 text-right text-sm text-blue-500">
                                        <a href="{{ route('post.list') }}">View More</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


{{-- caldender js --}}
<script src="{{ asset('js/homepage_calender.js') }}"></script>

