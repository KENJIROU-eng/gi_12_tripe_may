<x-app-layout>
    <div class= "mt-5">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5 text-black dark:text-gray-100">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-12 my-4">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Create Post</h1>
                    </div>
                    <div class="h-[2px] w-full bg-gradient-to-r from-yellow-400 via-orange-400 to-pink-400 my-4"></div>
                    {{-- contents --}}
                    <div class="mx-auto h-full mt-6 mb-24">
                        <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="w-3/4 mx-auto">

                                {{--title--}}
                                <div class="mb-2" x-data="{ count: 0 }">
                                    <label for="title" class="block text-sm font-semibold mb-1">Title</label>
                                    {{-- <input type="text" name="title" id="title" placeholder="Post title"
                                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:ring-orange-400 focus:border-orange-400"> --}}
                                    <input type="text" name="title" id="title" placeholder="Post title" maxlength="30" x-model="countValue" @input="count = $event.target.value.length" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:ring-orange-400 focus:border-orange-400">
                                    <div class="text-right text-sm text-gray-500 mt-1">
                                        <span x-text="count"></span>/30
                                    </div>
                                </div>
                                @error('title')
                                    <div class="text-red-500 text-xs">{{ $message }}</div>
                                @enderror

                                {{--image--}}
                                <div class="flex flex-col md:flex-row gap-4 mb-3">
                                    <div class="mt-2">
                                        <label for="image" class="block text-sm font-semibold mb-2">Image</label>
                                        <img id="image-preview" class="rounded-md  hidden max-h-[200px]" src="" alt="Image Preview" style="min-width: 100px; max-width: 300px; width: auto;">
                                    </div>
                                    {{-- <div class="flex flex-col justify-end md:justify-end items-start md:items-end">
                                        <input type="file" name="image" id="image" class="form-control" aria-describedby="image-info" onchange="previewImage(event)">
                                        <div class="form-text text-gray-500 mt-1 break-words" id="image-info">
                                            The acceptable formats are jpeg, jpg, png, and gif only. <br>
                                            Max file size is 2096kb.
                                        </div>
                                    </div> --}}
                                    <div class="flex flex-col justify-start sm:justify-end items-start sm:items-end w-full sm:w-auto">
                                        <input type="file" name="image" id="image" class="form-control w-full sm:w-auto"
                                            aria-describedby="image-info" onchange="previewImage(event)">
                                        <div class="form-text text-gray-500 mt-1 text-sm break-words" id="image-info">
                                            The acceptable formats are jpeg, jpg, png, and gif only. <br>
                                            Max file size is 2096kb.
                                        </div>
                                    </div>
                                </div>
                                @error('image')
                                    <div class="text-red-500 text-xs">{{ $message }}</div>
                                @enderror

                                {{--category--}}
                                <label  class="block text-sm font-semibold mb-1" for="category_name">Category</label>
                                <div class="max-h-20 space-y-2 mb-2 overflow-y-auto p-2 rounded">
                                    @forelse ($all_categories as $category)
                                        <label class="flex items-center space-x-3 cursor-pointer" for="category_name">
                                            <input type="checkbox" name="category_name[]" value="{{ $category->id }}" class="accent-orange-400">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-500">No Categories</p>
                                    @endforelse
                                </div>
                                @error('category_name')
                                    <div class="text-red-500 text-xs">{{ $message }}</div>
                                @enderror

                                {{--description--}}
                                <div x-data="{ count: 0, max: 500 }" class="mb-2">
                                    <label for="description" class="block text-sm font-semibold mb-1">Description</label>
                                    <textarea name="description" id="description" rows="3" cols="200"  maxlength="500" x-model="$el.value" @input="count = $event.target.value.length"
                                    class="w-full border border-gray-300 rounded focus:outline-none focus:ring focus:ring-orange-400 focus:border-orange-400"></textarea>
                                    <div class="text-right text-sm mt-1" :class="{ 'text-red-500': count >= max, 'text-gray-500': count < max }">
                                            <span x-text="count"></span>/<span x-text="max"></span>
                                    </div>
                                    @error('description')
                                        <div class="text-red-500 text-xs">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Itinerary 選択 --}}
                                <div class="mb-4">
                                    <label for="itinerary_id" class="block text-sm font-semibold mb-1">Select Itinerary</label>
                                    <select name="itinerary_id" id="itinerary-select" class="w-full border border-gray-300 rounded px-4 py-2">
                                        <option value="">-- Select an Itinerary --</option>
                                        @foreach ($user_itineraries as $itinerary)
                                            <option value="{{ $itinerary->id }}">{{ $itinerary->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- 目的地チェックボックス --}}
                                <div id="destinations-container" class="mb-6 hidden">
                                    <label class="block text-sm font-semibold mb-1">Select Destinations</label>
                                    <div id="destinations-list" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-60 overflow-y-auto pr-1">
                                        {{-- JavaScriptで生成される --}}
                                    </div>
                                </div>

                                <script>
                                    const itineraryData = @json($user_itineraries);

                                    document.getElementById('itinerary-select').addEventListener('change', function () {
                                        const selectedId = parseInt(this.value);
                                        const list = document.getElementById('destinations-list');
                                        const container = document.getElementById('destinations-container');
                                        list.innerHTML = '';

                                        if (!selectedId) {
                                            container.classList.add('hidden');
                                            return;
                                        }

                                        const itinerary = itineraryData.find(i => i.id === selectedId);
                                        const destinations = itinerary.date_itineraries.flatMap(d => d.map_itineraries);

                                        destinations.forEach(dest => {
                                            const label = document.createElement('label');
                                            label.className = 'flex items-center space-x-2 text-sm';
                                            label.innerHTML = `
                                                <input type="checkbox" name="destination_ids[]" value="${dest.id}" class="accent-orange-400">
                                                <span>${dest.place_name || dest.destination}</span>
                                            `;
                                            list.appendChild(label);
                                        });

                                        container.classList.remove('hidden');
                                    });
                                </script>


                                {{-- 公開範囲ラジオボタン --}}
                                <div class="mb-8">
                                    @php
                                        $visibilityOptions = [
                                            'public' => 'Everyone',
                                            'self' => 'Only Me',
                                            'followers' => 'Followers Only',
                                            'groups' => 'Specific Groups',
                                            'followers_groups' => 'Followers + Groups',
                                            'custom' => 'Selected Users'
                                        ];
                                    @endphp
                                    <label class="block text-sm font-semibold mb-1">Visible to:</label>
                                        <div class="grid grid-cols-1 md:flex md:flex-wrap gap-3">
                                        @foreach ($visibilityOptions as $value => $label)
                                            <label class="flex items-center px-4 py-2 border rounded-md cursor-pointer hover:bg-orange-50 transition">
                                                <input type="radio" name="visibility" value="{{ $value }}"
                                                    class="mr-3 text-orange-500 focus:ring-orange-400"
                                                    onchange="toggleVisibilityOptions()"
                                                    {{ $loop->first ? 'checked' : '' }}>
                                                <span class="text-sm">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div id="customUserList" class="mt-3 hidden border rounded p-3 bg-orange-50 mb-4">
                                    <label class="block font-semibold mb-2">Select Users to Share With:</label>

                                    {{-- 検索 & 一括ボタン --}}
                                    <div class="flex items-center justify-between mb-2">
                                        <input type="text" placeholder="Search users..." oninput="filterUsers(this, 'user-item')" class="border px-2 py-1 rounded text-sm w-1/2">
                                        <div class="space-x-2 text-sm">
                                            <button type="button" onclick="toggleAllCheckboxes('user-item', true)" class="text-blue-600 hover:underline">Select All</button>
                                            <button type="button" onclick="toggleAllCheckboxes('user-item', false)" class="text-red-600 hover:underline">Deselect All</button>
                                        </div>
                                    </div>

                                    {{-- ユーザー一覧 --}}
                                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-60 overflow-y-auto pr-1">
                                        @foreach ($all_users as $user)
                                            <label class="flex items-center space-x-2 text-sm user-item">
                                                <input type="checkbox" name="visible_users[]" value="{{ $user->id }}" class="accent-orange-400">
                                                <span>{{ $user->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div id="groupList" class="mt-3 hidden border rounded p-3 bg-orange-50 mb-4">
                                    <label class="block font-semibold mb-2">Select Groups to Share With:</label>

                                    {{-- 検索 & 一括ボタン --}}
                                    <div class="flex items-center justify-between mb-2">
                                        <input type="text" placeholder="Search groups..." oninput="filterUsers(this, 'group-item')" class="border px-2 py-1 rounded text-sm w-1/2">
                                        <div class="space-x-2 text-sm">
                                            <button type="button" onclick="toggleAllCheckboxes('group-item', true)" class="text-blue-600 hover:underline">Select All</button>
                                            <button type="button" onclick="toggleAllCheckboxes('group-item', false)" class="text-red-600 hover:underline">Deselect All</button>
                                        </div>
                                    </div>

                                    {{-- グループ一覧 --}}
                                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-60 overflow-y-auto pr-1">
                                        @foreach ($user_groups as $group)
                                            <label class="flex items-center space-x-2 text-sm group-item">
                                                <input type="checkbox" name="visible_groups[]" value="{{ $group->id }}" class="accent-orange-400">
                                                <span>{{ $group->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <script>
                                    function toggleVisibilityOptions() {
                                        const selected = document.querySelector('input[name="visibility"]:checked')?.value;

                                        const userList = document.getElementById('customUserList');
                                        const groupList = document.getElementById('groupList');

                                        userList.classList.toggle('hidden', selected !== 'custom');
                                        groupList.classList.toggle('hidden', selected !== 'groups');

                                        if (selected === 'followers_groups') {
                                            userList.classList.add('hidden');
                                            groupList.classList.add('hidden');
                                        }
                                    }

                                    function toggleAllCheckboxes(className, check) {
                                        document.querySelectorAll(`.${className} input[type="checkbox"]`).forEach(cb => cb.checked = check);
                                    }

                                    function filterUsers(input, className) {
                                        const keyword = input.value.toLowerCase();
                                        document.querySelectorAll(`.${className}`).forEach(item => {
                                            const text = item.innerText.toLowerCase();
                                            item.style.display = text.includes(keyword) ? '' : 'none';
                                        });
                                    }

                                    document.addEventListener('DOMContentLoaded', toggleVisibilityOptions);
                                </script>

                                {{--button--}}
                                <div class="grid grid-cols-4 w-full  gap-2">
                                    <div class="col-span-  col-start-2 w-full flex items-center justify-center py-2 border bg-white border-gray-400 rounded-md hover:bg-gray-300 duration-300">
                                        <a href="{{ route('post.list') }}" class="block text-center" >Cancel</a>
                                    </div>
                                    <div class="col-span-1 col-start-3 w-full">
                                        <button type="submit"
                                            class="w-full bg-yellow-300
                                                text-black font-semibold py-2 rounded-md transition duration-300
                                                hover:bg-gradient-to-r from-yellow-400 via-orange-400 to-pink-400 hover:text-white hover:shadow-lg">
                                            Create Post
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{--image preview--}}
                        <script src="{{ asset('js/previewImage.js') }}"></script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
