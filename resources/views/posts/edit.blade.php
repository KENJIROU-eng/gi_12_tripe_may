<x-app-layout>
    <div class= "mt-5">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-screen ">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5 text-black dark:text-gray-100">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-12 my-4">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Edit Post</h1>
                        <div class="flex ml-auto items-center">
                            <div class="col-auto bg-gray-500 rounded-full ml-4">
                                @if ($post->user->avatar)
                                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="object-cover rounded-full sm:w-11 sm:h-11 w-8 h-8">
                                @else
                                    <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="object-cover rounded-full sm:w-11 sm:h-11 w-8 h-8">
                                @endif
                            </div>
                            <a href="{{route('profile.show', $post->user->id)}}">
                                <div class="col-auto ml-2 text-xs sm:text-base">{{ $post->user->name }}</div>
                            </a>
                        </div>
                    </div>
                    <div class="h-[2px] w-full bg-gradient-to-r from-green-500 via-lime-500 to-emerald-500 my-2"></div>
                    {{-- contents --}}
                    <div class="mx-auto h-full mt-6 mb-24">
                        <form action="{{ route('post.update', $post->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="w-3/4 mx-auto">

                                {{--title--}}
                                <div class="mb-2" x-data="{ title: @js($post->title), count: {{ strlen($post->title) }} }">
                                    <label for="title" class="block text-sm font-semibold mb-1">Title</label>
                                    <input type="text" name="title" id="title" maxlength="30"
                                        x-model="title"
                                        @input="count = title.length"
                                        class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-100">
                                    <div class="text-right text-sm text-gray-500 mt-1">
                                        <span x-text="count"></span>/30
                                    </div>
                                </div>
                                @error('title')
                                    <div class="text-red-500 text-xs">{{ $message }}</div>
                                @enderror

                                <div class="flex flex-col md:flex-row gap-4 mb-4">
                                    <div class="mt-2">
                                        <label for="image" class="block text-sm font-semibold mb-2">Image</label>
                                        <img id="image-preview" class="rounded-md max-h-[200px] object-cover" src="{{ $post->image }}" alt="Image Preview" style="min-width: 100px; max-width: 300px; width: auto;">
                                    </div>
                                    <div class="flex flex-col justify-start md:justify-end items-start md:items-end w-full sm:w-auto">
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
                                        @if (in_array($category->id, $categoryPost_id))
                                            <input type="checkbox" name="category_name[]" value="{{ $category->id }}" checked>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                            </div>
                                        @else
                                            <input type="checkbox" name="category_name[]" value="{{ $category->id }}">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                            </div>
                                        @endif
                                    </label>
                                    @empty
                                        <p class="text-sm text-gray-500">No Categories</p>
                                    @endforelse
                                </div>
                                @error('category_name')
                                    <div class="text-red-500 text-xs">{{ $message }}</div>
                                @enderror

                                {{--description--}}
                                <div class="mb-4" x-data="{ count: 0, max: 500 }">
                                    <label for="description" class="block text-sm font-semibold mb-1">Description</label>
                                    <textarea name="description" id="description" rows="3" cols="200"  maxlength="500" x-model="$el.value" @input="count = $event.target.value.length"
                                    class="w-full border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-100">{{ $post->description }}</textarea>
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
                                        <option value="">-- No Itinerary --</option>
                                        @foreach ($user_itineraries as $itinerary)
                                            <option value="{{ $itinerary->id }}" {{ $post->itinerary_id == $itinerary->id ? 'selected' : '' }}>
                                                {{ $itinerary->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- 目的地チェックボックス --}}
                                <div id="destinations-container" class="mb-6 {{ $post->itinerary_id ? '' : 'hidden' }}">
                                    <label class="block text-sm font-semibold mb-1">Select Destinations</label>
                                    <div id="destinations-list" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-60 overflow-y-auto pr-1">
                                        {{-- JavaScriptで表示 --}}
                                    </div>
                                </div>

                                <script>
                                    function showDestinations(itineraryId) {
                                        document.querySelectorAll('.destination-group').forEach(group => {
                                            group.style.display = group.dataset.itinerary == itineraryId ? 'block' : 'none';
                                        });
                                    }

                                    document.addEventListener('DOMContentLoaded', function () {
                                        const itinerarySelect = document.querySelector('select[name="itinerary_id"]');
                                        const destinationGroups = document.querySelectorAll('.destination-group');

                                        if (itinerarySelect) {
                                            itinerarySelect.addEventListener('change', function () {
                                                const selectedId = this.value;
                                                destinationGroups.forEach(group => {
                                                    group.style.display = group.dataset.itinerary === selectedId ? 'block' : 'none';
                                                });
                                            });
                                        }
                                    });

                                    const itineraries = @json($user_itineraries);
                                    const selectedMapIds = @json($selected_map_itinerary_ids);

                                    function renderDestinations(itineraryId) {
                                        const container = document.getElementById('destinations-container');
                                        const list = document.getElementById('destinations-list');
                                        list.innerHTML = '';

                                        const itinerary = itineraries.find(i => i.id == itineraryId);
                                        if (!itinerary) {
                                            container.classList.add('hidden');
                                            return;
                                        }

                                        container.classList.remove('hidden');

                                        itinerary.date_itineraries.forEach(date => {
                                            date.map_itineraries.forEach(map => {
                                                const label = document.createElement('label');
                                                label.className = 'flex items-center space-x-2 text-sm';

                                                const checkbox = document.createElement('input');
                                                checkbox.type = 'checkbox';
                                                checkbox.name = 'map_itinerary_ids[]';
                                                checkbox.value = map.id;
                                                checkbox.className = 'accent-green-500';
                                                if (selectedMapIds.includes(map.id)) {
                                                    checkbox.checked = true;
                                                }

                                                const span = document.createElement('span');
                                                span.textContent = map.place_name || map.destination;

                                                label.appendChild(checkbox);
                                                label.appendChild(span);
                                                list.appendChild(label);
                                            });
                                        });
                                    }

                                    document.addEventListener('DOMContentLoaded', function () {
                                        const select = document.getElementById('itinerary-select');
                                        renderDestinations(select.value); // 初期表示（edit）

                                        select.addEventListener('change', function () {
                                            renderDestinations(this.value);
                                        });
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
                                            'custom' => 'Selected Users',
                                        ];
                                    @endphp

                                    <label class="block text-sm font-semibold mb-1">Visible to:</label>
                                    <div class="grid grid-cols-1 md:flex md:flex-wrap gap-3">
                                        @foreach ($visibilityOptions as $value => $label)
                                            <label class="flex items-center px-4 py-2 border rounded-md cursor-pointer hover:bg-green-50 transition">
                                                <input type="radio" name="visibility" value="{{ $value }}"
                                                    class="mr-3 text-green-500 focus:ring-green-400"
                                                    onchange="toggleVisibilityOptions()"
                                                    {{ $post->visibility === $value ? 'checked' : '' }}>
                                                <span class="text-sm">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- グループ選択 --}}
                                <div id="groupList" class="mt-3 border rounded p-3 bg-green-50 hidden">
                                    <label class="block font-semibold mb-2">Select Groups to Share With:</label>

                                    {{-- 検索＆一括操作 --}}
                                    <div class="flex items-center justify-between mb-2">
                                        <input type="text" placeholder="Search groups..." oninput="filterUsers(this, 'group-item')" class="border px-2 py-1 rounded text-sm w-1/2">
                                        <div class="space-x-2 text-sm">
                                            <button type="button" onclick="toggleAllCheckboxes('group-item', true)" class="text-blue-600 hover:underline">Select All</button>
                                            <button type="button" onclick="toggleAllCheckboxes('group-item', false)" class="text-red-600 hover:underline">Deselect All</button>
                                        </div>
                                    </div>

                                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-60 overflow-y-auto pr-1">
                                        @forelse ($user_groups as $group)
                                            <label class="flex items-center space-x-2 text-sm group-item">
                                                <input type="checkbox" name="visible_groups[]" value="{{ $group->id }}"
                                                    class="accent-green-400"
                                                    {{ $post->visibleGroups->contains($group->id) ? 'checked' : '' }}>
                                                <span>{{ $group->name }}</span>
                                            </label>
                                        @empty
                                            <div class="col-span-full text-sm text-gray-500">
                                                You have no group members available.<br>
                                                Please create a group to enable selection.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- 個別ユーザー選択 --}}
                                <div id="customUserList" class="mt-3 border rounded p-3 bg-green-50 hidden">
                                    <label class="block font-semibold mb-2">Select Users to Share With:</label>

                                    {{-- 検索＆一括操作 --}}
                                    <div class="flex items-center justify-between mb-2">
                                        <input type="text" placeholder="Search users..." oninput="filterUsers(this, 'user-item')" class="border px-2 py-1 rounded text-sm w-1/2">
                                        <div class="space-x-2 text-sm">
                                            <button type="button" onclick="toggleAllCheckboxes('user-item', true)" class="text-blue-600 hover:underline">Select All</button>
                                            <button type="button" onclick="toggleAllCheckboxes('user-item', false)" class="text-red-600 hover:underline">Deselect All</button>
                                        </div>
                                    </div>

                                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-60 overflow-y-auto pr-1">
                                        @forelse ($filteredUsers as $user)
                                            <label class="flex items-center space-x-2 text-sm user-item">
                                                <input type="checkbox" name="visible_users[]" value="{{ $user->id }}"
                                                    class="accent-green-400"
                                                    {{ $post->visibleUsers->contains($user->id) ? 'checked' : '' }}>
                                                <span>{{ $user->name }}</span>
                                            </label>
                                        @empty
                                            <div class="col-span-full text-sm text-gray-500">
                                                You have no followers or group members available.<br>
                                                Please follow someone or create a group to enable selection.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <script>
                                    function toggleVisibilityOptions() {
                                        const selected = document.querySelector('input[name="visibility"]:checked')?.value;

                                        // 各表示ブロック取得
                                        const userList = document.getElementById('customUserList');
                                        const groupList = document.getElementById('groupList');

                                        if (selected === 'custom') {
                                            userList.classList.remove('hidden');
                                            groupList.classList.add('hidden');
                                        } else if (selected === 'groups') {
                                            userList.classList.add('hidden');
                                            groupList.classList.remove('hidden');
                                        } else {
                                            // その他（public, self, followers, followers_groups など）は両方非表示
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
                                    <div class="col-span-1 col-start-2 w-full ">
                                        <a href="{{ route('post.show', $post->id) }}" class="block text-center w-full border border-gray-400 hover:bg-gray-300 py-2 text-black rounded-md">Cancel</a>
                                    </div>
                                    <div class="col-span-1 col-start-3 w-full">
                                        <button type="submit"
                                            class="w-full bg-green-500
                                                text-white py-2 rounded-md transition duration-300
                                                hover:bg-gradient-to-r  from-green-500 via-lime-500 to-emerald-500 hover:text-white hover:shadow-lg">
                                            Update
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
