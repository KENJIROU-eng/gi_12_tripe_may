
<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100 h-full">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Welcome to Tripe@s</h1>
                    </div>
                    {{-- contents --}}
                    <div class="mx-auto h-4/5 mt-8 overflow-hidden">
                        <div class="grid grid-cols-3 gap-4 h-full">
                            {{-- calender --}}
                            <div class="col-span-2 p-4 h-[720px]">
                                <div class="mb-4">
                                    <a href="{{ route('itinerary.share') }}" class="text-center bg-green-500 py-2 px-2 text-md lg:text-xl rounded-md lg:rounded-xl text-white inline-block w-64 lg:w-96">
                                        + Create Itinerary
                                    </a>
                                </div>
                                <div class="mx-auto bg-white shadow-lg rounded-lg h-4/5">
                                    <div class="px-6 py-4 flex justify-between items-center">
                                        <button id="prev-month" class="text-gray-500 hover:text-gray-700">&lt; Previous</button>
                                        <h2 id="month-year" class="text-xl font-semibold"></h2>
                                        <button id="next-month" class="text-gray-500 hover:text-gray-700">Next &gt;</button>
                                    </div>
                                    <div class="grid grid-cols-7 border-b border-gray-200">
                                        <div><p class="text-center py-2 font-semibold text-gray-600">Sun.</p></div>
                                        <div><p class="text-center py-2 font-semibold text-gray-600">Mon.</p></div>
                                        <div><p class="text-center py-2 font-semibold text-gray-600">Tue.</p></div>
                                        <div><p class="text-center py-2 font-semibold text-gray-600">Wed.</p></div>
                                        <div><p class="text-center py-2 font-semibold text-gray-600">Thu.</p></div>
                                        <div><p class="text-center py-2 font-semibold text-gray-600">Fri.</p></div>
                                        <div><p class="text-center py-2 font-semibold text-gray-600">Sat.</p></div>
                                    </div>
                                    <div id="calendar-body" class="grid grid-cols-7 h-4/5"></div>
                                </div>
                            </div>

                            {{-- ranking --}}
                            <div class="col-span-1 p-4 h-[720px]">
                                <div class="h-[600px] rounded-lg border-2">
                                    <div class="mt-2 text-center text-lg">
                                        <h2>
                                            Post Ranking
                                        </h2>
                                    </div>
                                    <div class="grid grid-rows-3 h-4/5 mt-6 gap-10">
                                        <div class="grid grid-rows-4">
                                            <div class="row-span-1 mx-auto text-yellow-300">
                                                <i class="fa-solid fa-crown"></i> 1st
                                                <a href="" class="text-black">Moal Boal</a>
                                            </div>
                                            <div class="row-span-3">
                                                <div class="grid grid-cols-3 h-[96px]">
                                                    <div class="col-span-2">
                                                        <img src="#" alt="#" class="bg-blue-500 h-1/2 aspect-square mx-auto">
                                                    </div>
                                                    <div class="col-span-1 h-[96px] flex items-end py-3">
                                                        <i class="fa-solid fa-heart text-red-500 me-3 text-xl"></i>
                                                        <span>1233</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grid grid-rows-4">
                                            <div class="row-span-1 mx-auto text-slate-400">
                                                <i class="fa-solid fa-crown"></i> 2nd
                                                <a href="" class="text-black">Moal Boal</a>
                                            </div>
                                            <div class="row-span-3">
                                                <div class="grid grid-cols-3 h-[96px]">
                                                    <div class="col-span-2">
                                                        <img src="#" alt="#" class="bg-blue-500 h-1/2 aspect-square mx-auto">
                                                    </div>
                                                    <div class="col-span-1 h-[96px] flex items-end py-3">
                                                        <i class="fa-solid fa-heart text-red-500 me-3 text-xl"></i>
                                                        <span>1233</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grid grid-rows-4">
                                            <div class="row-span-1 mx-auto text-yellow-700">
                                                <i class="fa-solid fa-crown"></i> 3rd
                                                <a href="" class="text-black">Moal Boal</a>
                                            </div>
                                            <div class="row-span-3">
                                                <div class="grid grid-cols-3 h-[96px]">
                                                    <div class="col-span-2">
                                                        <img src="#" alt="#" class="bg-blue-500 h-1/2 aspect-square mx-auto">
                                                    </div>
                                                    <div class="col-span-1 h-[96px] flex items-end py-3">
                                                        <i class="fa-solid fa-heart text-red-500 me-3 text-xl"></i>
                                                        <span>1233</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-end text-sm text-blue-500 px-3">
                                        <a href="#">View More</a>
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

