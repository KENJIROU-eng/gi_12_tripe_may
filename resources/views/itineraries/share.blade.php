<x-app-layout>
    <div class="py-4 min-h-screen bg-cover bg-center" style="background-image: url('https://res.cloudinary.com/dpwrycc89/image/upload/v1750757614/pexels-jplenio-1133505_ijwxpn.jpg');">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-8">
                {{-- タイトル --}}
                <div class="text-center mb-10">
                    <h1 class="text-4xl md:text-6xl font-bold text-gray-800 dark:text-gray-100">
                        <i class="fa-solid fa-s"></i>
                        <i class="fa-solid fa-h"></i>
                        <i class="fa-solid fa-a"></i>
                        <i class="fa-solid fa-r"></i>
                        <i class="fa-solid fa-e"></i>
                    </h1>
                </div>

                {{-- 説明文 --}}
                <div class="text-center space-y-6">
                    <p class="text-sm md:text-base text-gray-600 dark:text-gray-300">
                        Would you like to share your itinerary with other users?<br>
                        If you share it, other users will be able to edit or delete it.
                    </p>

                    {{-- フォーム --}}
                    <form action="{{ route('itinerary.prefill') }}" id="shareForm" method="get" class="space-y-6 max-w-md mx-auto">
                        @csrf

                        {{-- YES / NO ボタン --}}
                        <div class="flex flex-wrap justify-center gap-4">
                            <label class="cursor-pointer group relative w-32 sm:w-36 transition-transform duration-300 hover:scale-105">
                                <input type="radio" name="share" value="yes" class="peer hidden">
                                <div class="px-5 py-3 text-center rounded-xl bg-gradient-to-r from-blue-400 to-blue-600 text-white shadow-md
                                            transition-all duration-300 ease-in-out peer-checked:from-green-400 peer-checked:to-green-600
                                            peer-checked:ring-4 peer-checked:ring-green-300">
                                    <i class="fa-solid fa-share-nodes mr-2"></i> YES
                                </div>
                            </label>

                            <label class="cursor-pointer group relative w-32 sm:w-36 transition-transform duration-300 hover:scale-105">
                                <input type="radio" name="share" value="no" class="peer hidden">
                                <div class="px-5 py-3 text-center rounded-xl bg-gradient-to-r from-gray-400 to-gray-600 text-white shadow-md
                                            transition-all duration-300 ease-in-out peer-checked:from-green-400 peer-checked:to-green-600
                                            peer-checked:ring-4 peer-checked:ring-green-300">
                                    <i class="fa-solid fa-lock mr-2"></i> NO
                                </div>
                            </label>
                        </div>

                        @error('share')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror

                        {{-- グループ選択エリア（YES） --}}
                        <div id="userSelectArea" class="overflow-hidden transition-all duration-500 max-h-0 opacity-0">
                            <label for="group" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Select a group to share with:</label>
                            <select name="group" id="group" class="w-full border rounded px-3 py-2">
                                <option value="">-- Please select --</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                            @error('group')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NO選択時のメッセージ --}}
                        <div id="noMessage" class="hidden text-sm text-gray-600 dark:text-gray-300">
                            I will create the itinerary without sharing it.<br>
                            You can choose to share it later if you want.
                        </div>

                        {{-- NEXTボタン --}}
                        <div class="text-center">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded shadow-md transition">
                                NEXT
                            </button>
                        </div>
                    </form>
                </div>

                {{-- スクリプト --}}
                <script>
                    const yesRadio = document.querySelector('input[value="yes"]');
                    const noRadio = document.querySelector('input[value="no"]');
                    const userSelectArea = document.getElementById('userSelectArea');
                    const noMessage = document.getElementById('noMessage');

                    function toggleUserSelect() {
                        if (yesRadio.checked) {
                            userSelectArea.classList.remove('max-h-0', 'opacity-0');
                            userSelectArea.classList.add('max-h-40', 'opacity-100');
                            noMessage.classList.add('hidden');
                        } else if (noRadio.checked) {
                            userSelectArea.classList.remove('max-h-40', 'opacity-100');
                            userSelectArea.classList.add('max-h-0', 'opacity-0');
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
</x-app-layout>
