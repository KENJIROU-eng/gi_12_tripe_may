<x-app-layout>
    <div class="py-12 h-[880px]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-4xl md:text-6xl font-bold absolute left-1/2 transform -translate-x-1/2">Sharing</h1>
                    </div>

                    {{-- contents --}}
                    <div class="max-w-6xl mx-auto h-full mt-8 flex flex-col justify-center items-center">
                        <p class="text-center mb-6">
                            Would you like to share your itinerary with other users?<br>
                            If you share it, other users will be able to edit or delete it.
                        </p>
                        <form action="{{ route('itinerary.prefill') }}" id="shareForm" method="get" class="w-full max-w-md">
                            @csrf

                            <div class="flex justify-center my-5">
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="share" value="yes" class="peer opacity-0 absolute w-6 h-6">
                                    <div class="px-4 py-2 border rounded-lg bg-blue-500 text-white peer-checked:bg-green-500 peer-checked:text-white">YES</div>
                                </label>
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="share" value="no" class="peer opacity-0 absolute w-6 h-6">
                                    <div class="px-4 py-2 border rounded-lg bg-gray-500 text-white peer-checked:bg-green-500 peer-checked:text-white">NO</div>
                                </label>
                            </div>
                            @error('share')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror


                            {{-- YES Message --}}
                            <div id="userSelectArea" class="hidden mb-4">
                                <label for="group" class="block mb-1">Select a group to share with:</label>
                                <select name="group" id="group" class="border rounded px-2 py-1 w-full">
                                    <option value="">-- Please select --</option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                                @error('group')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- NO Message --}}
                            <div id="noMessage" class="hidden mb-4 text-gray-600">
                                I will create the itinerary without sharing it.<br>
                                You can choose to share it later if you want.
                            </div>

                            {{-- button --}}
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">NEXT</button>
                        </form>
                    </div>

                    <script>
                        const yesRadio = document.querySelector('input[value="yes"]');
                        const noRadio = document.querySelector('input[value="no"]');
                        const userSelectArea = document.getElementById('userSelectArea');
                        const noMessage = document.getElementById('noMessage');

                        function toggleUserSelect() {
                            if (yesRadio.checked) {
                                userSelectArea.classList.remove('hidden');
                                noMessage.classList.add('hidden');
                            } else {
                                userSelectArea.classList.add('hidden');
                                noMessage.classList.remove('hidden');
                            }
                        }

                        toggleUserSelect();

                        yesRadio.addEventListener('change', toggleUserSelect);
                        noRadio.addEventListener('change', toggleUserSelect);

                    </script>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>


